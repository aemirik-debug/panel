<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo 'tenancy.database.template_tenant_connection=' . var_export(config('tenancy.database.template_tenant_connection'), true) . PHP_EOL;
echo 'tenancy.database.central_connection=' . var_export(config('tenancy.database.central_connection'), true) . PHP_EOL;
echo 'database.connections.tenant.driver=' . var_export(config('database.connections.tenant.driver'), true) . PHP_EOL;
echo 'database.connections.tenant.database=' . var_export(config('database.connections.tenant.database'), true) . PHP_EOL;
