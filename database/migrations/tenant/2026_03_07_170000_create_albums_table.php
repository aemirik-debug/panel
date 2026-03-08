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
            Schema::create('albums', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('slug')->nullable()->index();
                $table->json('show_on')->nullable();
                $table->json('images')->nullable();
                $table->integer('order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('galleries')) {
            return;
        }

        $existingAlbums = DB::table('albums')->count();
        if ($existingAlbums > 0) {
            return;
        }

        $images = DB::table('galleries')
            ->where('is_active', true)
            ->whereNotNull('image')
            ->orderBy('order', 'asc')
            ->pluck('image')
            ->filter()
            ->values()
            ->all();

        if (empty($images)) {
            return;
        }

        $now = now();

        DB::table('albums')->insert([
            'title' => 'Aktarilan Foto Galeri',
            'slug' => 'aktarilan-foto-galeri',
            'show_on' => json_encode(['home']),
            'images' => json_encode($images),
            'order' => 0,
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('albums');
    }
};
