<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('categories', 'parent_id')) {
            Schema::table('categories', function (Blueprint $table) {
                // SQLite tarafında sorunsuz ilerlemek için yalnızca kolon ekleniyor.
                $table->unsignedBigInteger('parent_id')->nullable()->after('slug');
            });
        }

        // Kategori tipi artık kullanılmıyor, tüm mevcut kayıtları ürün kategorisi yap.
        if (Schema::hasColumn('categories', 'type')) {
            DB::table('categories')->update(['type' => 'product']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('categories', 'parent_id')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropColumn('parent_id');
            });
        }
    }
};
