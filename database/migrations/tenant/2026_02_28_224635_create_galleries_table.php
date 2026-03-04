<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
			$table->foreignId('menu_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('title')->nullable(); // Fotoğrafa başlık girmek istersek diye (zorunlu değil)
            $table->string('image'); // Fotoğrafın kendisi
            $table->integer('order')->default(0); // Sürükle-bırak sıralama için
            $table->boolean('is_active')->default(true); // Aktif/Pasif
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galleries');
    }
};