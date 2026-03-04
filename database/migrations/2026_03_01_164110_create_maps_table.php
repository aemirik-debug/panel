<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maps', function (Blueprint $table) {
            $table->id();
            $table->string('page')->nullable(); // Haritanın hangi sayfada görüneceği
            $table->string('title'); // Harita Başlığı (Örn: İstanbul Ofis)
            $table->text('iframe_code'); // Google Maps'ten alınan o uzun <iframe> kodu
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maps');
    }
};