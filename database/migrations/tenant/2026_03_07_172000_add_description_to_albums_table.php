<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('albums') || Schema::hasColumn('albums', 'description')) {
            return;
        }

        Schema::table('albums', function (Blueprint $table): void {
            $table->text('description')->nullable()->after('title');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('albums') || !Schema::hasColumn('albums', 'description')) {
            return;
        }

        Schema::table('albums', function (Blueprint $table): void {
            $table->dropColumn('description');
        });
    }
};
