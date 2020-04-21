<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\TablePaginate;

class PaymentStatus extends Model
{
    use TablePaginate;

    protected $fillable = ['status'];

    protected $searchable = ['status'];

    /*
     |--------------------------------------------------------------------------
     | Relationships
     |--------------------------------------------------------------------------
     |
     */
    public function payment(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
