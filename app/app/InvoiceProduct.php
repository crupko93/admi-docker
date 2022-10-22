<?php

namespace App;

use App\Traits\OwnershipMethods;
use App\Traits\TablePaginate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceProduct extends Model
{
    use OwnershipMethods, TablePaginate;

    protected $fillable = ['invoice_id', 'summary', 'price', 'currency'];

    protected $searchable = ['summary', 'price', 'currency'];

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
