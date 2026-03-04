<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Etkinlik Adı
            $table->text('description')->nullable(); // Etkinlik Detayı
            $table->string('location')->nullable(); // Etkinlik Yeri/Adresi
            $table->dateTime('start_date'); // Başlangıç Tarihi ve Saati
            $table->dateTime('end_date')->nullable(); // Bitiş Tarihi ve Saati (İsteğe bağlı)
            $table->boolean('is_active')->default(true); // Yayında mı?
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};