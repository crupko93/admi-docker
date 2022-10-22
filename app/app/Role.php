<?php

namespace App;

use App\Traits\TablePaginate;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Models\Role as SpatieRole;
use Spatie\Permission\Traits\HasRoles;

class Role extends SpatieRole
{
    use TablePaginate;

    protected array $searchable = ['name'];

    /*
     |--------------------------------------------------------------------------
     | Accessors
     |--------------------------------------------------------------------------
     |
     */
    public function getLabelAttribute()
    {
        return ucwords(str_replace('_', ' ', $this->name));
    }
}
