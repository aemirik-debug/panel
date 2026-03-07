<?php

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
        Schema::table('settings', function (Blueprint $table) {
            $table->boolean('use_custom_mail_settings')->default(false)->after('send_contact_notifications');
            $table->string('mail_driver')->nullable()->after('use_custom_mail_settings');
            $table->string('mail_host')->nullable()->after('mail_driver');
            $table->integer('mail_port')->nullable()->after('mail_host');
            $table->string('mail_username')->nullable()->after('mail_port');
            $table->text('mail_password')->nullable()->after('mail_username'); // Encrypted
            $table->string('mail_encryption')->nullable()->after('mail_password');
            $table->string('mail_from_address')->nullable()->after('mail_encryption');
            $table->string('mail_from_name')->nullable()->after('mail_from_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'use_custom_mail_settings',
                'mail_driver',
                'mail_host',
                'mail_port',
                'mail_username',
                'mail_password',
                'mail_encryption',
                'mail_from_address',
                'mail_from_name',
            ]);
        });
    }
};
