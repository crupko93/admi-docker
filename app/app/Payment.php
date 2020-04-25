<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\TablePaginate;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use TablePaginate;

    protected $fillable = ['booking_id', 'payment_status_id', 'method', 'price', 'currency'];

    protected $searchable = ['method', 'price', 'currency'];

    /*
     |--------------------------------------------------------------------------
     | Relationships
     |--------------------------------------------------------------------------
     |
     */
    public function payment_status(): BelongsTo
    {
        return $this->belongsTo(PaymentStatus::class);
    }
}
