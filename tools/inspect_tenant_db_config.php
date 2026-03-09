<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$tenants = App\Models\Tenant::all();

foreach ($tenants as $tenant) {
    $data = $tenant->getAttributes();

    echo "Tenant: {$tenant->id}" . PHP_EOL;
    echo "  package: " . ($tenant->package ?? 'null') . PHP_EOL;
    echo "  tenancy_db_connection: " . ($data['tenancy_db_connection'] ?? 'null') . PHP_EOL;
    echo "  internal db_connection: " . (($tenant->getInternal('db_connection') ?? 'null')) . PHP_EOL;
    echo "  tenancy_db_name: " . ($data['tenancy_db_name'] ?? 'null') . PHP_EOL;
    echo "  internal db_name: " . (($tenant->getInternal('db_name') ?? 'null')) . PHP_EOL;
    echo "  tenancy_db_host: " . ($data['tenancy_db_host'] ?? 'null') . PHP_EOL;
    echo "  tenancy_db_port: " . ($data['tenancy_db_port'] ?? 'null') . PHP_EOL;
    echo "  database accessor: " . ($tenant->database()->getName() ?? 'null') . PHP_EOL;
    echo str_repeat('-', 50) . PHP_EOL;
}
