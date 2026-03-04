<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modal_settings', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Modal Başlığı
            $table->text('content')->nullable(); // Duyuru Metni
            $table->boolean('is_active')->default(false); // Aktif mi?
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modal_settings');
    }
};