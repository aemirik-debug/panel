<?php

use Illuminate\Support\Facades\Artisan;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;

// Her tenant için sütunu manuel ekle
$tenants = Tenant::all();

foreach ($tenants as $tenant) {
    echo "Processing tenant: {$tenant->id}\n";
    $tenant->run(function () use ($tenant) {
        try {
            // SQLite için ALTER TABLE ile sütun ekle
            DB::statement('ALTER TABLE posts ADD COLUMN is_active INTEGER DEFAULT 1 NOT NULL');
            echo "  ✓ is_active column added to posts table\n";
        } catch (\Exception $e) {
            if (str_contains($e->getMessage(), 'duplicate column')) {
                echo "  ℹ is_active column already exists\n";
            } else {
                echo "  ✗ Error: " . $e->getMessage() . "\n";
            }
        }
    });
}

echo "\nDone!\n";
