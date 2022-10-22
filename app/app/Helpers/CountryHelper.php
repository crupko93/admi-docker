<?php

namespace App\Helpers;

use Exception;
use PragmaRX\Countries\Package\Countries;

class CountryHelper
{
    /**
     * Return country name based on given country code
     *
     * @param  string    $country_code the country code to lookup
     * @throws Exception
     * @return string
     */
    public static function countryByCode($country_code)
    {
        $country = (new Countries)
            ->where('cca2', $country_code)
            ->first();

        if (empty($country)) {
            throw new Exception(sprintf('Unrecognized country code `%s`', $country_code));
        }

        return $country->name->common;
    }
}
