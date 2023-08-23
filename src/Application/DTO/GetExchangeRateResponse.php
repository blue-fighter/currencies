<?php

namespace Application\DTO;

/**
 * Response schema for exchange rate and the difference from the previous day
 */
readonly class GetExchangeRateResponse
{
    public function __construct(
        public string $targetDayRate,
        public string $rateChange,
    )
    {
    }
}
