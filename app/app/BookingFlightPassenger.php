<?php

namespace App;

use App\Traits\TablePaginate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingFlightPassenger extends Model
{
    use TablePaginate;

    protected $fillable = [
        'booking_fight_id',
        'first_name',
        'last_name',
        'date_birth',
        'gender',
        'country',
        'document_type',
        'number',
        'date_emitting',
        'date_expiring'
    ];

    protected $searchable = [
        'first_name',
        'last_name',
        'date_birth',
        'gender',
        'country',
        'document_type',
        'number',
        'date_emitting',
        'date_expiring'
    ];

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
