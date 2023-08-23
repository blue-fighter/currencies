<?php

namespace Application\Factories;

use Application\DTO\CreateCurrencyRequestDTO;
use Application\Entities\Currency;

class CurrencyFactory implements CurrencyFactoryInterface
{
    public function create(CreateCurrencyRequestDTO $request): Currency
    {
        return new Currency(
            $request->numericCode,
            $request->characterCode,
            $request->nominal,
            $request->name,
            $request->value,
            $request->date
        );
    }
}