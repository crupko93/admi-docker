<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Traits\{OwnershipMethods,TablePaginate};

class Booking extends Model
{
    use OwnershipMethods, TablePaginate;

    protected $fillable = ['user_id', 'booking_code', 'status', 'total_price', 'currency', 'referrer', 'source', 'language'];

    protected $searchable = ['booking_code', 'status', 'total_price', 'currency', 'referrer', 'source', 'language'];

    /*
     |--------------------------------------------------------------------------
     | Relationships
     |--------------------------------------------------------------------------
     |
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bookingCommunications(): HasMany
    {
        return $this->hasMany(BookingCommunication::class);
    }

    public function bookingBillings(): HasOne
    {
        return $this->hasOne(BookingBilling::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function bookingFlights(): HasMany
    {
        return $this->hasMany(BookingFlight::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function bookingRentals(): HasMany
    {
        return $this->hasMany(BookingRental::class);
    }
}
