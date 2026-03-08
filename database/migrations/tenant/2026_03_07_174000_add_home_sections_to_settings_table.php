<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('settings')) {
            return;
        }

        if (! Schema::hasColumn('settings', 'home_sections')) {
            Schema::table('settings', function (Blueprint $table): void {
                $table->json('home_sections')->nullable()->after('show_home_gallery_button');
            });
        }

        if (! Schema::hasColumn('settings', 'home_sections')) {
            return;
        }

        $defaults = [
            ['key' => 'split_slider', 'label' => 'Bolunmus Slider', 'is_visible' => true],
            ['key' => 'services', 'label' => 'Hizmetler', 'is_visible' => true],
            ['key' => 'cta', 'label' => 'Harekete Gec', 'is_visible' => true],
            ['key' => 'references', 'label' => 'Referanslar', 'is_visible' => true],
            ['key' => 'gallery', 'label' => 'Foto Galeri Akisi', 'is_visible' => true],
            ['key' => 'posts', 'label' => 'Son Yazilar', 'is_visible' => true],
        ];

        DB::table('settings')
            ->whereNull('home_sections')
            ->update(['home_sections' => json_encode($defaults, JSON_UNESCAPED_UNICODE)]);
    }

    public function down(): void
    {
        if (! Schema::hasTable('settings') || ! Schema::hasColumn('settings', 'home_sections')) {
            return;
        }

        Schema::table('settings', function (Blueprint $table): void {
            $table->dropColumn('home_sections');
        });
    }
};
