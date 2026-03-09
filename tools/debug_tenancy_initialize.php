<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$tenants = App\Models\Tenant::all();

foreach ($tenants as $tenant) {
    echo "Init tenant {$tenant->id} ... ";

    try {
        tenancy()->initialize($tenant);
        echo "OK" . PHP_EOL;
        tenancy()->end();
    } catch (Throwable $e) {
        echo "FAIL: " . $e->getMessage() . PHP_EOL;
        echo "Template: " . var_export($tenant->database()->getTemplateConnectionName(), true) . PHP_EOL;
        $tpl = $tenant->database()->getTemplateConnectionName();
        echo "Driver: " . var_export(config("database.connections.{$tpl}.driver"), true) . PHP_EOL;
    }
}
