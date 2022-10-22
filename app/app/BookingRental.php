<?php

namespace App;

use App\Traits\TablePaginate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BookingRental extends Model
{
    use TablePaginate;

    protected $fillable = ['booking_id', 'total_price', 'commission'];

    protected $searchable = ['total_price', 'commission'];

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

    public function bookingRentalCars(): HasMany
    {
        return $this->hasMany(BookingRentalCar::class);
    }

    public function bookingRentalAdditionalServices(): HasMany
    {
        return $this->hasMany(BookingRentalAdditionalService::class);
    }

    public function bookingRentalDrivers(): HasMany
    {
        return $this->hasMany(BookingRentalDriver::class);
    }
}
