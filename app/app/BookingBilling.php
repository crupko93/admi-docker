<?php

namespace App;

use App\Traits\TablePaginate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingBilling extends Model
{
    use TablePaginate;

    protected $fillable = [
        'booking_id',
        'address_id',
        'first_name',
        'last_name',
        'is_company',
        'company_name',
        'vat',
        'email',
        'phone'
    ];

    protected $searchable = [
        'first_name',
        'last_name',
        'is_company',
        'company_name',
        'vat',
        'email',
        'phone'
    ];

    /*
     |--------------------------------------------------------------------------
     | Relationships
     |--------------------------------------------------------------------------
     |
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
