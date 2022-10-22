<?php

namespace App;

use App\Traits\TablePaginate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingRentalAdditionalService extends Model
{
    use TablePaginate;

    protected $fillable = ['booking_rental_id', 'type', 'status', 'price', 'currency'];

    protected $searchable = ['type', 'status', 'price', 'currency'];

    /*
     |--------------------------------------------------------------------------
     | Relationships
     |--------------------------------------------------------------------------
     |
    */
    public function bookingRental(): BelongsTo
    {
        return $this->belongsTo(BookingRental::class);
    }
}
