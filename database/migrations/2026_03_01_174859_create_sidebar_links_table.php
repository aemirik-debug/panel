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
       Schema::create('sidebar_links', function (Blueprint $table) {
    $table->id();
    $table->string('page')->nullable(); // Bağlı Olduğu Sayfa (Senin resmindeki gibi)
    $table->string('link_title'); // Slider Başlığı / Link Adı
    $table->string('url')->nullable(); // Gideceği adres
    $table->integer('sort_order')->default(0);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sidebar_links');
    }
};
