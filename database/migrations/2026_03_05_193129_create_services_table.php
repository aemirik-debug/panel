<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            
            // 1. İÇERİK BİLGİLERİ
            $table->string('title'); 
            $table->string('slug')->unique(); 
            $table->text('short_description')->nullable(); 
            $table->longText('content')->nullable(); 
            
            // 2. MEDYA YÖNETİMİ
            $table->string('icon')->nullable(); 
            $table->string('image')->nullable(); 
            
            // 3. SEO (ARAMA MOTORU) KONTROLÜ
            $table->string('meta_title')->nullable(); 
            $table->string('meta_description', 160)->nullable(); 
            
            // 4. SİSTEM DURUMU
            $table->boolean('is_active')->default(true); 
            $table->integer('sort_order')->default(0); 
            
            $table->timestamps(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};