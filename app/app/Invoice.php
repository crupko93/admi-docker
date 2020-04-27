<?php

namespace App;

use App\Traits\OwnershipMethods;
use App\Traits\TablePaginate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use OwnershipMethods, TablePaginate;

    protected $fillable = ['booking_id', 'number', 'series'];

    protected $searchable = ['number', 'series'];

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

    public function invoiceProducts(): HasMany
    {
        return $this->hasMany(InvoiceProduct::class);
    }

    public function invoiceBillings(): HasMany
    {
        return $this->hasMany(InvoiceBilling::class);
    }
}
