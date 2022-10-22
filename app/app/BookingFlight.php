<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\TablePaginate;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BookingFlight extends Model
{
    use TablePaginate;

    protected $fillable = ['booking_id', 'total_price', 'commission', 'currency'];

    protected $searchable = ['total_price', 'commission', 'currency'];

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

    public function bookingFlightPnrs(): HasMany
    {
        return $this->hasMany(BookingFlightPnr::class);
    }

    public function bookingFlightAdditionalServices(): HasMany
    {
        return $this->hasMany(BookingFlightAdditionalService::class);
    }

    public function bookingFlightRoutes(): HasMany
    {
        return $this->hasMany(BookingFlightRoute::class);
    }

    public function bookingFlightPassengers(): HasMany
    {
        return $this->hasMany(BookingFlightPassenger::class);
    }

    public function bookingFlightLuggage(): HasMany
    {
        return $this->hasMany(BookingFlightLuggage::class);
    }

    public function bookingFlightMetadata(): HasMany
    {
        return $this->hasMany(BookingFlightMetadata::class);
    }
}
