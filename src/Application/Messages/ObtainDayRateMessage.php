<?php

namespace Application\Messages;
use DateTime;

class ObtainDayRateMessage
{
    private DateTime $target_day;

    /**
     * @param DateTime $target_day
     */
    public function __construct(DateTime $target_day)
    {
        $this->target_day = $target_day;
    }

    /**
     * @return DateTime
     */
    public function getTargetDay(): DateTime
    {
        return $this->target_day;
    }

}