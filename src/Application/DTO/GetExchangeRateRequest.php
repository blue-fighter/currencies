<?php

namespace Application\DTO;

use Application\Validators;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Http request to get the exchange rate and the difference from the previous day
 */
readonly class GetExchangeRateRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Validators\CBRDate]
        public ?string $date,
        #[Assert\NotBlank]
        #[Assert\Currency]
        public ?string   $targetCurrencyCode,
        #[Assert\Currency]
        public ?string   $sourceCurrencyCode,
    )
    {
    }
}