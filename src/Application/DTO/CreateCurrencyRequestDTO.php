<?php

namespace Application\DTO;

use DateTime;

readonly class CreateCurrencyRequestDTO
{
    public function __construct(
        public ?string   $numericCode,
        public ?string   $characterCode,
        public ?int      $nominal,
        public ?string   $name,
        public ?int      $value,
        public DateTime $date
    )
    {
    }
}