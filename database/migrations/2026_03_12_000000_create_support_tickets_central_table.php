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
        Schema::create('support_tickets_central', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->nullable();
            $table->string('tenant_domain')->nullable();
            $table->string('user_name');
            $table->string('user_email');
            $table->string('category')->nullable();
            $table->string('title');
            $table->longText('message');
            $table->string('screenshot')->nullable();
            $table->string('status')->default('yeni');
            $table->boolean('is_read')->default(false);
            $table->longText('admin_reply')->nullable();
            $table->boolean('customer_notified')->default(false);
            $table->timestamps();

            $table->index('tenant_id');
            $table->index('is_read');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_tickets_central');
    }
};
