<?php

namespace App;

use App\Traits\TablePaginate;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
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
