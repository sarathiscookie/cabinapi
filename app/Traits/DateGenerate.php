<?php

namespace App\Traits;

use DatePeriod;
use DateTime;
use DateInterval;

Trait DateGenerate {

    /**
     * Generates a date between two dates.
     *
     * @param  string  $now
     * @param  string  $end
     * @return Date $period
     */
    protected function generateDates($now, $end){
        $period = new DatePeriod(
            new DateTime($now),
            new DateInterval('P1D'),
            new DateTime($end)
        );

        return $period;
    }
}