<?php

use App\Models\Setting;
use App\Models\SocialMedia;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('social_media')) {
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

        // Seed one row from settings if available and table is empty.
        try {
            if (Schema::hasTable('settings') && ! SocialMedia::exists()) {
                $setting = Setting::query()->first();

                SocialMedia::create([
                    'facebook' => $setting->facebook ?? null,
                    'instagram' => $setting->instagram ?? null,
                    'twitter' => $setting->twitter ?? null,
                    'linkedin' => $setting->linkedin ?? null,
                    'whatsapp_number' => $setting->whatsapp_number ?? null,
                    'whatsapp_message' => 'Merhaba, size nasil yardimci olabilirim?',
                ]);
            }
        } catch (\Throwable $e) {
            // Do not fail migration because of optional data copy.
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
