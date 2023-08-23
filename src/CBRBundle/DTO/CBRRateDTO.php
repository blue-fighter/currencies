<?php

namespace CBRBundle\DTO;

use DateTime;

/**
 * Currency Rate from Central Bank of Russian Federation
 */
readonly class CBRRateDTO
{
    public function __construct(
        public ?string   $numericCode,
        public ?string   $characterCode,
        public ?string   $nominal,
        public ?string   $name,
        public ?string   $value,
        public DateTime $date
    )
    {
    }
}