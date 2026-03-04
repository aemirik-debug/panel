<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->string('page')->nullable(); // Bağlı Olduğu Sayfa
            $table->string('title'); // Sınav Adı
            $table->json('questions')->nullable(); // Tüm sorular, şıklar ve doğru cevaplar burada tutulacak
            $table->boolean('is_active')->default(true); // Aktif/Pasif durumu
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};