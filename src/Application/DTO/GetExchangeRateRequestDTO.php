<?php

namespace Application\DTO;

use Application\Entities\Currency;
use DateTime;


readonly class GetExchangeRateRequestDTO
{
    public function __construct(
        public DateTime $date,
        public string   $targetCurrencyCode,
        public string   $sourceCurrencyCode = Currency::PIVOT_CURRENCY_CHARACTER_CODE,
    )
    {
    }
}