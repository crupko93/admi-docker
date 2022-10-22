<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\CountryHelper;

class Address extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'country_code',
        'address',
        'address_line_2',
        'city',
        'state',
        'zip',
    ];

    /*
     |--------------------------------------------------------------------------
     | Accessors
     |--------------------------------------------------------------------------
     |
     */

    public function getCountryAttribute()
    {
        // TODO FIX Class 'PragmaRX\Countries\Package\Countries' not found !!!
         return empty($this->country_code)
             ? null
             : CountryHelper::countryByCode($this->country_code);
    }

    public function getFullAttribute()
    {
        return sprintf('%s %s, %s, %s %s %s',
            $this->address,
            $this->address_line_2,
            $this->city,
            $this->state,
            $this->zip,
            $this->country
        );
    }
}
