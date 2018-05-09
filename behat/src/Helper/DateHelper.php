<?php

namespace App\Behat\Helper;

class DateHelper
{

    /**
     * @param string $dateString
     * @param string $format
     * @return \DateTime
     */
    public function date(string $dateString, $format = 'Y-m-d'): \DateTime
    {
        return \DateTime::createFromFormat($format, $dateString);
    }

    /**
     * @param \DateTime $date
     * @param string $sub
     * @return \DateTime
     */
    public function dateSub(\DateTime $date, string $sub): \DateTime
    {
        $newDate = clone $date;
        $newDate->sub(\DateInterval::createFromDateString($sub));
        return $newDate;
    }

    /**
     * @param string $dateIn
     * @return \DateTime
     */
    public function dateIn(string $dateIn): \DateTime
    {
        $date = new \DateTime();
        $date->setTimestamp(strtotime($dateIn));
        return $date;
    }
}
