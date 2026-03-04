<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->string('form_name')->default('İletişim Formu')->after('id'); // Hangi formdan geldiği
            $table->text('note')->nullable()->after('message'); // Tablo üzerinden alınacak notlar
        });
    }

    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn(['form_name', 'note']);
        });
    }
};