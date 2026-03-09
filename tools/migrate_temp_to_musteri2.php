<?php

$base = __DIR__ . '/../database/';
$sourcePath = $base . 'tenant_temp.sqlite';
$targetPath = $base . 'tenantmusteri2';
$backupPath = $base . 'tenantmusteri2.backup_' . date('Ymd_His');

if (!file_exists($sourcePath)) {
    fwrite(STDERR, "Source database not found: {$sourcePath}\n");
    exit(1);
}
if (!file_exists($targetPath)) {
    fwrite(STDERR, "Target database not found: {$targetPath}\n");
    exit(1);
}

if (!copy($targetPath, $backupPath)) {
    fwrite(STDERR, "Backup failed.\n");
    exit(1);
}

echo "Backup created: {$backupPath}\n";

$exclude = [
    'migrations',
    'users',
    'sessions',
    'password_reset_tokens',
    'jobs',
    'job_batches',
    'failed_jobs',
    'cache',
    'cache_locks',
    'sqlite_sequence',
];

$src = new PDO('sqlite:' . $sourcePath);
$dst = new PDO('sqlite:' . $targetPath);
$src->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dst->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$srcTablesStmt = $src->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
$srcTables = $srcTablesStmt->fetchAll(PDO::FETCH_COLUMN);

$dstTablesStmt = $dst->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
$dstTables = $dstTablesStmt->fetchAll(PDO::FETCH_COLUMN);
$dstTableSet = array_flip($dstTables);

$dst->exec('PRAGMA foreign_keys = OFF');
$dst->beginTransaction();

try {
    foreach ($srcTables as $table) {
        if (in_array($table, $exclude, true)) {
            continue;
        }
        if (!isset($dstTableSet[$table])) {
            continue;
        }

        $srcColsStmt = $src->query("PRAGMA table_info({$table})");
        $srcCols = array_map(fn($r) => $r['name'], $srcColsStmt->fetchAll(PDO::FETCH_ASSOC));

        $dstColsStmt = $dst->query("PRAGMA table_info({$table})");
        $dstCols = array_map(fn($r) => $r['name'], $dstColsStmt->fetchAll(PDO::FETCH_ASSOC));

        $commonCols = array_values(array_intersect($srcCols, $dstCols));
        if (empty($commonCols)) {
            continue;
        }

        $colList = implode(',', array_map(fn($c) => '"' . $c . '"', $commonCols));

        $rows = $src->query("SELECT {$colList} FROM {$table}")->fetchAll(PDO::FETCH_ASSOC);

        $dst->exec("DELETE FROM {$table}");

        if (!empty($rows)) {
            $placeholders = implode(',', array_fill(0, count($commonCols), '?'));
            $insertSql = "INSERT INTO {$table} ({$colList}) VALUES ({$placeholders})";
            $insertStmt = $dst->prepare($insertSql);

            foreach ($rows as $row) {
                $insertStmt->execute(array_values($row));
            }
        }

        echo str_pad($table, 20) . ' copied rows: ' . count($rows) . "\n";
    }

    $dst->commit();
    $dst->exec('PRAGMA foreign_keys = ON');
    echo "\nData migration completed successfully.\n";
} catch (Throwable $e) {
    $dst->rollBack();
    $dst->exec('PRAGMA foreign_keys = ON');
    fwrite(STDERR, 'Migration failed: ' . $e->getMessage() . "\n");
    exit(1);
}
