<?php

namespace Application\Messages;

use DateTime;

class CollectDataMessage
{
    private DateTime $first_day;
    private DateTime $last_day;

    /**
     * @param DateTime $first_day
     * @param DateTime $last_day
     */
    public function __construct(DateTime $first_day, DateTime $last_day)
    {
        $this->first_day = $first_day;
        $this->last_day = $last_day;
    }

    /**
     * @return DateTime
     */
    public function getFirstDay(): DateTime
    {
        return $this->first_day;
    }

    /**
     * @return DateTime
     */
    public function getLastDay(): DateTime
    {
        return $this->last_day;
    }

}