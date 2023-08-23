<?php

namespace Application\Factories;
use Application\DTO\CreateCurrencyRequestDTO;
use Application\Entities\Currency;

interface CurrencyFactoryInterface
{
    public function create(CreateCurrencyRequestDTO $request): Currency;
}