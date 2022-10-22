<?php

namespace App;

use App\Traits\TablePaginate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingFlightAdditionalService extends Model
{
    use TablePaginate;

    protected $fillable = ['booking_fight_id', 'type', 'status', 'price', 'currency'];

    protected $searchable = ['type', 'status', 'price', 'currency'];

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
