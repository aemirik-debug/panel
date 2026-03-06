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
        if (!Schema::hasTable('settings')) {
            return;
        }

        Schema::table('settings', function (Blueprint $table) {
            $table->string('services_section_title')->nullable()->after('hero_background');
            $table->text('services_description')->nullable()->after('services_section_title');
            $table->string('cta_title')->nullable()->after('services_description');
            $table->text('cta_description')->nullable()->after('cta_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('settings')) {
            return;
        }

        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'services_section_title',
                'services_description',
                'cta_title',
                'cta_description',
            ]);
        });
    }
};
