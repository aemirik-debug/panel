<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            if (!Schema::hasColumn('menus', 'parent_id')) {
                $table->foreignId('parent_id')->nullable()->constrained('menus')->cascadeOnDelete()->after('order');
            }
            if (!Schema::hasColumn('menus', 'slug')) {
                $table->string('slug')->nullable()->unique()->after('url');
            }
            if (!Schema::hasColumn('menus', 'description')) {
                $table->text('description')->nullable()->after('slug');
            }
            if (!Schema::hasColumn('menus', 'icon')) {
                $table->string('icon')->nullable()->after('description');
            }
            if (!Schema::hasColumn('menus', 'meta_title')) {
                $table->string('meta_title')->nullable()->after('icon');
            }
            if (!Schema::hasColumn('menus', 'meta_description')) {
                $table->text('meta_description')->nullable()->after('meta_title');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            if (Schema::hasColumn('menus', 'parent_id')) {
                $table->dropForeign(['parent_id']);
                $table->dropColumn('parent_id');
            }
            if (Schema::hasColumn('menus', 'slug')) {
                $table->dropUnique(['slug']);
                $table->dropColumn('slug');
            }
            $table->dropColumn(array_filter([
                Schema::hasColumn('menus', 'description') ? 'description' : null,
                Schema::hasColumn('menus', 'icon') ? 'icon' : null,
                Schema::hasColumn('menus', 'meta_title') ? 'meta_title' : null,
                Schema::hasColumn('menus', 'meta_description') ? 'meta_description' : null,
            ]));
        });
    }
};
