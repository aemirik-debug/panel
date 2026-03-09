<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

$newPassword = '12345678';

foreach (Tenant::all() as $tenant) {
    tenancy()->initialize($tenant);

    $email = 'admin@' . $tenant->id . '.com';

    $user = User::firstOrCreate(
        ['email' => $email],
        [
            'name' => 'Musteri Yoneticisi',
            'password' => Hash::make($newPassword),
        ]
    );

    $user->name = $user->name ?: 'Musteri Yoneticisi';
    $user->password = Hash::make($newPassword);
    $user->email_verified_at = $user->email_verified_at ?? now();
    $user->save();

    $ok = Hash::check($newPassword, $user->fresh()->password) ? 'ok' : 'fail';

    echo $tenant->id . ' -> ' . $email . ' / ' . $newPassword . ' [' . $ok . ']' . PHP_EOL;

    tenancy()->end();
}
