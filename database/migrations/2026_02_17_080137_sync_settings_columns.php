<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {

            $columns = [
                'linkedin' => fn() => $table->string('linkedin')->nullable(),
                'footer_text' => fn() => $table->text('footer_text')->nullable(),
                'meta_title' => fn() => $table->string('meta_title')->nullable(),
                'meta_description' => fn() => $table->text('meta_description')->nullable(),

                'primary_color' => fn() => $table->string('primary_color')->nullable(),
                'secondary_color' => fn() => $table->string('secondary_color')->nullable(),

                'hero_title' => fn() => $table->string('hero_title')->nullable(),
                'hero_subtitle' => fn() => $table->text('hero_subtitle')->nullable(),
                'hero_button_text' => fn() => $table->string('hero_button_text')->nullable(),
                'hero_button_link' => fn() => $table->string('hero_button_link')->nullable(),
                'hero_background' => fn() => $table->string('hero_background')->nullable(),
            ];

            foreach ($columns as $name => $callback) {
                if (!Schema::hasColumn('settings', $name)) {
                    $callback();
                }
            }

        });
    }

    public function down(): void
    {
        //
    }
};