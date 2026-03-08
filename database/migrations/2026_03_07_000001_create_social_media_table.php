<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;
use App\Models\SocialMedia;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabloyu oluştur (eğer yoksa)
        if (!Schema::hasTable('social_media')) {
            Schema::create('social_media', function (Blueprint $table) {
                $table->id();
                $table->string('facebook')->nullable();
                $table->string('instagram')->nullable();
                $table->string('twitter')->nullable();
                $table->string('linkedin')->nullable();
                $table->string('whatsapp_number')->nullable();
                $table->text('whatsapp_message')->nullable();
                $table->timestamps();
            });
        }

        // Veri taşıma işlemini try-catch ile koru
        try {
            // Mevcut Setting verisinden sosyal medya bilgilerini kopyala
            $setting = Setting::first();
            if ($setting && !SocialMedia::exists()) {
                SocialMedia::create([
                    'facebook' => $setting->facebook ?? null,
                    'instagram' => $setting->instagram ?? null,
                    'twitter' => $setting->twitter ?? null,
                    'linkedin' => $setting->linkedin ?? null,
                    'whatsapp_number' => $setting->whatsapp_number ?? null,
                    'whatsapp_message' => 'Merhaba, size nasıl yardımcı olabilirim?',
                ]);
            }
        } catch (\Exception $e) {
            // Veri taşıma başarısız olursa migration'ı durdurmadan devam et
            \Log::warning('Social media data migration skipped: ' . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_media');
    }
};
