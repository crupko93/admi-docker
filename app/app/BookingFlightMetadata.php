<?php

namespace App;

use App\Traits\TablePaginate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingFlightMetadata extends Model
{
    use TablePaginate;

    protected $fillable = ['booking_fight_id', 'type', 'value'];

    protected $searchable = ['type', 'value'];

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
