<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Raw SQL ile direkt ekle (SQLite için)
        try {
            DB::statement('ALTER TABLE posts ADD COLUMN is_active INTEGER DEFAULT 1 NOT NULL');
        } catch (\Exception $e) {
            // Sütun zaten varsa hata vermesin
            if (!str_contains($e->getMessage(), 'duplicate column')) {
                throw $e;
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('posts', 'is_active')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->dropColumn('is_active');
            });
        }
    }
};
