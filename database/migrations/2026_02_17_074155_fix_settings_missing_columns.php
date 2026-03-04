<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {

            if (!Schema::hasColumn('settings', 'linkedin')) {
                $table->string('linkedin')->nullable();
            }

            if (!Schema::hasColumn('settings', 'primary_color')) {
                $table->string('primary_color')->nullable();
            }

            if (!Schema::hasColumn('settings', 'secondary_color')) {
                $table->string('secondary_color')->nullable();
            }

            if (!Schema::hasColumn('settings', 'hero_title')) {
                $table->string('hero_title')->nullable();
            }

            if (!Schema::hasColumn('settings', 'hero_subtitle')) {
                $table->text('hero_subtitle')->nullable();
            }

            if (!Schema::hasColumn('settings', 'hero_button_text')) {
                $table->string('hero_button_text')->nullable();
            }

            if (!Schema::hasColumn('settings', 'hero_button_link')) {
                $table->string('hero_button_link')->nullable();
            }

            if (!Schema::hasColumn('settings', 'hero_background')) {
                $table->string('hero_background')->nullable();
            }

        });
    }

    public function down(): void
    {
        //
    }
};
