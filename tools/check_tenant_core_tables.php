<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$tables = ['users', 'sessions', 'sliders', 'settings', 'menus', 'announcements'];

foreach (App\Models\Tenant::all() as $tenant) {
    tenancy()->initialize($tenant);

    $status = [];
    foreach ($tables as $table) {
        $status[] = $table . '=' . (Illuminate\Support\Facades\Schema::hasTable($table) ? 'ok' : 'missing');
    }

    echo $tenant->id . ': ' . implode(', ', $status) . PHP_EOL;

    tenancy()->end();
}
