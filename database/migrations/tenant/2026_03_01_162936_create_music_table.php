<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('music', function (Blueprint $table) {
            $table->id();
            $table->string('page')->nullable(); // Sayfa sütunu artık doğrudan burada!
            $table->string('title'); 
            $table->string('artist')->nullable(); 
            $table->string('file_path'); 
            $table->boolean('is_active')->default(true); 
            $table->integer('sort_order')->default(0); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('music');
    }
};