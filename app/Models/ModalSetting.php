<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class ModalSetting extends Model
{
    use BelongsToTenant;
    //
}
