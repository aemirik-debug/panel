<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_results', function (Blueprint $table) {
            $table->id();
            $table->string('quiz_title'); // Çözülen sınavın adı
            $table->string('user_name'); // Çözenin Adı Soyadı
            $table->string('user_email')->nullable(); // E-postası
            $table->string('user_phone')->nullable(); // Telefonu
            $table->json('details')->nullable(); // Hangi soruya ne cevap verdiğini tutacağımız esnek alan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_results');
    }
};