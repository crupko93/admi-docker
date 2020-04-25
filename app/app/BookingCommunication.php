<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\TablePaginate;
use Illuminate\Database\Eloquent\Model;

class BookingCommunication extends Model
{
    use TablePaginate;

    protected $fillable = ['booking_id', 'booking_communication_report_id', 'type', 'content', 'status', 'recipient'];

    protected $searchable = ['type', 'content', 'status', 'recipient'];

    /*
     |--------------------------------------------------------------------------
     | Relationships
     |--------------------------------------------------------------------------
     |
     */
    public function bookingCommunicationReports(): HasMany
    {
        return $this->hasMany(BookingCommunicationReport::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
