<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (! Schema::hasColumn('settings', 'references_section_title')) {
                $table->string('references_section_title')->nullable()->after('contact_notification_email');
            }
            if (! Schema::hasColumn('settings', 'references_section_description')) {
                $table->text('references_section_description')->nullable()->after('references_section_title');
            }
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (Schema::hasColumn('settings', 'references_section_title')) {
                $table->dropColumn('references_section_title');
            }
            if (Schema::hasColumn('settings', 'references_section_description')) {
                $table->dropColumn('references_section_description');
            }
        });
    }
};
