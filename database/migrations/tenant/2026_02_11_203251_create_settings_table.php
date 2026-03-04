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
        Schema::create('settings', function (Blueprint $table) {
        $table->id();

        $table->string('site_name')->nullable();
        $table->string('logo')->nullable();
        $table->string('favicon')->nullable();

        $table->string('phone')->nullable();
        $table->string('email')->nullable();
        $table->string('address')->nullable();

        $table->string('facebook')->nullable();
        $table->string('instagram')->nullable();
        $table->string('twitter')->nullable();
        $table->string('linkedin')->nullable();

        $table->text('footer_text')->nullable();

        $table->string('meta_title')->nullable();
        $table->text('meta_description')->nullable();

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
