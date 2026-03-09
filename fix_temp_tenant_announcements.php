<?php

$dbPath = __DIR__ . '/database/tenant_temp.sqlite';

echo "Creating announcements table in: $dbPath\n";

try {
    $pdo = new PDO("sqlite:$dbPath");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create announcements table
    $sql = "CREATE TABLE IF NOT EXISTS announcements (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        content TEXT NOT NULL,
        image TEXT,
        button_text TEXT,
        button_url TEXT,
        type TEXT DEFAULT 'modal',
        color_scheme TEXT DEFAULT 'primary',
        starts_at DATETIME,
        ends_at DATETIME,
        is_active INTEGER DEFAULT 1,
        view_count INTEGER DEFAULT 0,
        created_at DATETIME,
        updated_at DATETIME
    )";
    
    $pdo->exec($sql);
    echo "✓ Announcements table created!\n";
    
    // Create index
    $indexSql = "CREATE INDEX IF NOT EXISTS idx_announcements_active 
                 ON announcements(is_active, starts_at, ends_at)";
    $pdo->exec($indexSql);
    echo "✓ Index created!\n";
    
    echo "\nSuccess! You can now use the admin panel.\n";
    
} catch (PDOException $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
