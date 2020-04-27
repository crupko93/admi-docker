<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\TablePaginate;
use Illuminate\Database\Eloquent\Model;

class BookingCommunicationReport extends Model
{
    use TablePaginate;

    protected $fillable = ['booking_communication_id'];

    protected $searchable = [];

    /*
     |--------------------------------------------------------------------------
     | Relationships
     |--------------------------------------------------------------------------
     |
     */
    public function bookingCommunication(): BelongsTo
    {
        return $this->belongsTo(BookingCommunication::class);
    }
}
