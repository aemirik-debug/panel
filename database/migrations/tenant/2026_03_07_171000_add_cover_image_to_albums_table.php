<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('albums')) {
            return;
        }

        if (!Schema::hasColumn('albums', 'cover_image')) {
            Schema::table('albums', function (Blueprint $table): void {
                $table->string('cover_image')->nullable()->after('images');
            });
        }

        DB::table('albums')
            ->whereNull('cover_image')
            ->orderBy('id')
            ->chunkById(100, function ($albums): void {
                foreach ($albums as $album) {
                    $images = json_decode($album->images ?? '[]', true);
                    $coverImage = is_array($images) && !empty($images) ? $images[0] : null;

                    if ($coverImage) {
                        DB::table('albums')
                            ->where('id', $album->id)
                            ->update(['cover_image' => $coverImage]);
                    }
                }
            });
    }

    public function down(): void
    {
        if (!Schema::hasTable('albums') || !Schema::hasColumn('albums', 'cover_image')) {
            return;
        }

        Schema::table('albums', function (Blueprint $table): void {
            $table->dropColumn('cover_image');
        });
    }
};
