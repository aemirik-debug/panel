<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'send_contact_notifications')) {
                $table->boolean('send_contact_notifications')->default(false)->after('google_analytics');
            }

            if (!Schema::hasColumn('settings', 'contact_notification_email')) {
                $table->string('contact_notification_email')->nullable()->after('send_contact_notifications');
            }
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (Schema::hasColumn('settings', 'contact_notification_email')) {
                $table->dropColumn('contact_notification_email');
            }

            if (Schema::hasColumn('settings', 'send_contact_notifications')) {
                $table->dropColumn('send_contact_notifications');
            }
        });
    }
};
