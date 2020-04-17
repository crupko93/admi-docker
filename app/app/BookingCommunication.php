<?php

namespace App;

use App\Traits\{TablePaginate};
use Illuminate\Database\Eloquent\Model;

class BookingCommunication extends Model
{
    use OwnershipMethods, TablePaginate;

    protected $fillable = ['user_id', 'booking_code', 'status', 'price', 'currency', 'referrer', 'source', 'language'];

    protected $searchable = ['booking_code', 'status', 'price', 'currency', 'referrer', 'source', 'language'];
}
