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
        Schema::connection('tenant')->table('sliders', function (Blueprint $table) {
            if (!Schema::connection('tenant')->hasColumn('sliders', 'slides')) {
                // JSON column to store multiple slides (max 10)
                // Each slide: {image, title, subtitle}
                $table->json('slides')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->table('sliders', function (Blueprint $table) {
            if (Schema::connection('tenant')->hasColumn('sliders', 'slides')) {
                $table->dropColumn('slides');
            }
        });
    }
};
