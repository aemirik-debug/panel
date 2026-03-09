<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

$tenants = Tenant::all();

foreach ($tenants as $tenant) {
    tenancy()->initialize($tenant);

    $email = 'admin@' . $tenant->id . '.com';

    $user = User::firstOrCreate(
        ['email' => $email],
        [
            'name' => 'Müşteri Yöneticisi',
            'password' => Hash::make('12345678'),
        ]
    );

    echo $tenant->id . ': ' . $email . ' (' . ($user->wasRecentlyCreated ? 'created' : 'exists') . ')' . PHP_EOL;

    tenancy()->end();
}
