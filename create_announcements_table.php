<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

$tenants = App\Models\Tenant::all();

foreach ($tenants as $tenant) {
    echo "Creating table for tenant: {$tenant->id}\n";
    
    tenancy()->initialize($tenant);
    
    // Check if table exists
    if (Schema::hasTable('announcements')) {
        echo "  ✓ Table already exists, skipping...\n";
        tenancy()->end();
        continue;
    }
    
    // Create table
    try {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('content');
            $table->string('image')->nullable();
            $table->string('button_text')->nullable();
            $table->string('button_url')->nullable();
            $table->enum('type', ['modal', 'banner', 'popup'])->default('modal');
            $table->enum('color_scheme', ['primary', 'success', 'warning', 'danger', 'info'])->default('primary');
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('view_count')->default(0);
            $table->timestamps();
            
            $table->index(['is_active', 'starts_at', 'ends_at']);
        });
        
        echo "  ✓ Table created successfully!\n";
    } catch (\Exception $e) {
        echo "  ✗ Error: " . $e->getMessage() . "\n";
    }
    
    tenancy()->end();
}

echo "\nAll done!\n";
