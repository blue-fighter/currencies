<?php

namespace Application\Factories;

use Application\DTO\CreateCurrencyRequestDTO;
use CBRBundle\DTO\CBRRateDTO;

interface CreateCurrencyRequestFactoryInterface
{
    public function createFromCBRRate(CBRRateDTO $rateDTO): CreateCurrencyRequestDTO;
}