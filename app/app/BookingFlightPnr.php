<?php

namespace App;

use App\Traits\TablePaginate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingFlightPnr extends Model
{
    use TablePaginate;

    protected $fillable = ['booking_fight_id', 'booking_code', 'pnr', 'status', 'ticket_code'];

    protected $searchable = ['booking_code', 'pnr', 'status', 'ticket_code'];

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
