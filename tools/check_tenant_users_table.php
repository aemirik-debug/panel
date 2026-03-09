<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$tenants = App\Models\Tenant::all();

foreach ($tenants as $tenant) {
    tenancy()->initialize($tenant);

    $hasUsers = Illuminate\Support\Facades\Schema::hasTable('users') ? 'yes' : 'no';
    $usersCount = $hasUsers === 'yes' ? App\Models\User::count() : 0;

    echo "{$tenant->id}: users_table={$hasUsers}, users_count={$usersCount}" . PHP_EOL;

    tenancy()->end();
}
