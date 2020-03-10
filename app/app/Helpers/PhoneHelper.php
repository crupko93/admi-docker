<?php

namespace App\Helpers;

use Exception;

use Propaganistas\LaravelPhone\PhoneNumber;

class PhoneHelper
{
    /**
     * Format a given raw phone number into US friendly display format
     * If the given number does not match the US phone number pattern, it is returned as-is
     *
     * @param  string $number phone number to format
     * @return string
     */
    public static function formatUS($number)
    {
        if (preg_match('/^\+\d(\d{3})(\d{3})(\d{4})$/', $number, $groups)) {
            return sprintf('(%s) %s-%s', $groups[1], $groups[2], $groups[3]);
        }

        return $number;
    }

    /**
     * Format a given raw phone number in accordance to international formatting rules
     * US numbers are formatted differently using friendly display format
     *
     * @param  string $number phone number to format
     * @return string
     */
    public static function formatInternational($number)
    {
        try {
            $phoneNumber = PhoneNumber::make($number);

            return $phoneNumber->isOfCountry('US')
                ? self::formatUS($number)
                : $phoneNumber->formatInternational();
        } catch (Exception $e) {
            return $number;
        }
    }
}
