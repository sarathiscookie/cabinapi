<?php

namespace App\Traits;

use DateTime;

Trait DateFormat {
    /**
     * Generates date format as mongo.
     *
     * @param  string  $date
     * @return Date $utcDateTime
     */
    protected function getDateUtc($date)
    {
        $dateFormatChange = DateTime::createFromFormat("d.m.y", $date)->format('Y-m-d');
        $dateTime         = new DateTime($dateFormatChange);
        $timeStamp        = $dateTime->getTimestamp();
        $utcDateTime      = new \MongoDB\BSON\UTCDateTime($timeStamp * 1000);

        return $utcDateTime;
    }
}