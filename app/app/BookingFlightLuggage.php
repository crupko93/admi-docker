<?php

namespace App;

use App\Traits\TablePaginate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingFlightLuggage extends Model
{
    use TablePaginate;

    protected $fillable = ['booking_fight_id', 'booking_code', 'price'];

    protected $searchable = ['booking_code', 'price'];

    /*
     |--------------------------------------------------------------------------
     | Relationships
     |--------------------------------------------------------------------------
     |
    */
    public function bookingFlight(): BelongsTo
    {
        return $this->belongsTo(BookingFlight::class);
    }
}
