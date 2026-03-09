<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$tenants = App\Models\Tenant::all();

foreach ($tenants as $tenant) {
    $dbConfig = $tenant->database();
    $template = $dbConfig->getTemplateConnectionName();
    $driver = config("database.connections.{$template}.driver");
    $dbName = $dbConfig->getName();

    echo "Tenant {$tenant->id}" . PHP_EOL;
    echo "  template=" . var_export($template, true) . PHP_EOL;
    echo "  driver=" . var_export($driver, true) . PHP_EOL;
    echo "  dbName=" . var_export($dbName, true) . PHP_EOL;

    try {
        $exists = $dbConfig->manager()->databaseExists($dbName);
        echo "  exists=" . ($exists ? 'yes' : 'no') . PHP_EOL;
    } catch (Throwable $e) {
        echo "  manager_error=" . $e->getMessage() . PHP_EOL;
    }

    echo str_repeat('-', 40) . PHP_EOL;
}
