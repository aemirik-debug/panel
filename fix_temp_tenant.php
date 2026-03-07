<?php

require __DIR__.'/vendor/autoload.php';

use Illuminate\Support\Facades\DB;

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// tenant_temp veritabanına doğrudan bağlan
$pdo = new PDO('sqlite:database/tenant_temp.sqlite');

try {
    $pdo->exec('ALTER TABLE posts ADD COLUMN is_active INTEGER DEFAULT 1 NOT NULL');
    echo "✓ is_active column added to tenant_temp.sqlite\n";
} catch (PDOException $e) {
    if (str_contains($e->getMessage(), 'duplicate column')) {
        echo "ℹ is_active column already exists in tenant_temp.sqlite\n";
    } else {
        echo "✗ Error: " . $e->getMessage() . "\n";
    }
}
