<?php

namespace App;

use App\Traits\TablePaginate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingRentalDriver extends Model
{
    use TablePaginate;

    protected $fillable = ['booking_rental_id', 'first_name', 'last_name', 'date_birth', 'country'];

    protected $searchable = ['first_name', 'last_name', 'date_birth', 'country'];

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
