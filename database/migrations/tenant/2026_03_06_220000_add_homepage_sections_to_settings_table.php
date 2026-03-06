<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'services_section_title')) {
                $table->string('services_section_title')->nullable()->after('hero_background');
            }

            if (!Schema::hasColumn('settings', 'services_description')) {
                $table->text('services_description')->nullable()->after('services_section_title');
            }

            if (!Schema::hasColumn('settings', 'cta_title')) {
                $table->string('cta_title')->nullable()->after('services_description');
            }

            if (!Schema::hasColumn('settings', 'cta_description')) {
                $table->text('cta_description')->nullable()->after('cta_title');
            }
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $dropColumns = [];

            if (Schema::hasColumn('settings', 'services_section_title')) {
                $dropColumns[] = 'services_section_title';
            }

            if (Schema::hasColumn('settings', 'services_description')) {
                $dropColumns[] = 'services_description';
            }

            if (Schema::hasColumn('settings', 'cta_title')) {
                $dropColumns[] = 'cta_title';
            }

            if (Schema::hasColumn('settings', 'cta_description')) {
                $dropColumns[] = 'cta_description';
            }

            if (!empty($dropColumns)) {
                $table->dropColumn($dropColumns);
            }
        });
    }
};
