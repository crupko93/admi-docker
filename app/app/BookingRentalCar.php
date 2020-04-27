<?php

namespace App;

use App\Traits\TablePaginate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingRentalCar extends Model
{
    use TablePaginate;

    protected $fillable = ['booking_rental_id'];

    protected $searchable = [];

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
