<?php

$base = __DIR__ . '/../database/';
$dbFiles = [
    'database.sqlite',
    'tenant_temp.sqlite',
    'tenantmusteri2',
    'tenantmusteri1',
    'tenantmusteri3',
];

$tables = [
    'services','sliders','settings','menus','pages','posts','categories','products','social_media','announcements','contacts','portfolios','albums','comments','galleries'
];

foreach ($dbFiles as $file) {
    $path = $base . $file;
    if (!file_exists($path)) {
        continue;
    }

    echo "=== {$file} ===" . PHP_EOL;
    try {
        $pdo = new PDO('sqlite:' . $path);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        foreach ($tables as $table) {
            $existsStmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='{$table}'");
            $exists = (bool) $existsStmt->fetchColumn();
            if (!$exists) {
                continue;
            }

            $count = (int) $pdo->query("SELECT COUNT(*) FROM {$table}")->fetchColumn();
            if ($count > 0) {
                echo str_pad($table, 16) . " : {$count}" . PHP_EOL;
            }
        }
    } catch (Throwable $e) {
        echo 'ERR: ' . $e->getMessage() . PHP_EOL;
    }
    echo PHP_EOL;
}
