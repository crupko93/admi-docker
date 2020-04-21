<?php

namespace App;

use App\Traits\TablePaginate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingFlightRoute extends Model
{
    use TablePaginate;

    protected $fillable = ['booking_fight_id', 'airline', 'departure_date', 'arrival_date', 'departure', 'arrival', 'booking_code'];

    protected $searchable = ['airline', 'departure_date', 'arrival_date', 'departure', 'arrival', 'booking_code'];

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
