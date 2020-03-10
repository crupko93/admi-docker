<?php

namespace App\Helpers;

class DateTimeHelper
{
    /**
     * Format a Carbon datetime, optionally according to given format string.
     *
     * @param \Carbon\Carbon $carbon
     * @param string         $format (optional)
     * @param mixed          $diffHoursHuman (optional) display human format only if diff is less than this value
     *
     * @return string
     */
    public static function friendlyFormat($carbon, $format = 'Y-M-d \a\t h:i a', $diffHoursHuman = 12)
    {
        $carbon->tz('UTC');

        if (!$diffHoursHuman || $carbon->diffInHours() <= $diffHoursHuman) {
            return $carbon->diffForHumans();
        } elseif ($carbon->isToday()) {
            return sprintf('today at %s', $carbon->format('h:i a'));
        } elseif ($carbon->isYesterday()) {
            return sprintf('yesterday at %s', $carbon->format('h:i a'));
        }

        return $carbon->format($format);
    }
}
