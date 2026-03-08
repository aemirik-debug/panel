<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\TenantConnection;

class ModalSetting extends Model
{
    use TenantConnection;
    //
}
