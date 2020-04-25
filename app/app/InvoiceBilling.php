<?php

namespace App;

use App\Traits\OwnershipMethods;
use App\Traits\TablePaginate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceBilling extends Model
{
    use OwnershipMethods, TablePaginate;

    protected $fillable = [
        'invoice_id',
        'address_id',
        'first_name',
        'last_name',
        'is_company',
        'company',
        'vat',
        'email',
        'phone'
    ];

    protected $searchable = [
        'first_name',
        'last_name',
        'is_company',
        'company',
        'vat',
        'email',
        'phone'
    ];

    /*
     |--------------------------------------------------------------------------
     | Relationships
     |--------------------------------------------------------------------------
     |
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
