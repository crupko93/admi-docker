<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\{OwnershipMethods,TablePaginate};

class Booking extends Model
{
    use OwnershipMethods, TablePaginate;

    protected $fillable = ['user_id', 'booking_code', 'status', 'price', 'currency', 'referrer', 'source', 'language'];

    protected $searchable = ['booking_code', 'status', 'price', 'currency', 'referrer', 'source', 'language'];
}
