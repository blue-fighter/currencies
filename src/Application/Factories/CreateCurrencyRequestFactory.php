<?php

namespace Application\Factories;

use Application\DTO\CreateCurrencyRequestDTO;
use CBRBundle\DTO\CBRRateDTO;

class CreateCurrencyRequestFactory implements CreateCurrencyRequestFactoryInterface
{
    public function createFromCBRRate(CBRRateDTO $rateDTO): CreateCurrencyRequestDTO
    {
        return new CreateCurrencyRequestDTO(
            $rateDTO->numericCode,
            $rateDTO->characterCode,
            $this->modifyNominal($rateDTO->nominal),
            $rateDTO->name,
            $this->modifyValue($rateDTO->value),
            $rateDTO->date,
        );
    }

    /**
     * Modifies string value to int.
     * Last 4 digits is fractional part
     * @param string|null $value
     * @return int|null
     */
    private function modifyValue(?string $value): ?int
    {
        return null !== $value ? (int)str_replace(',','', $value) : null;
    }

    /**
     * Modifies string nominal to int
     * @param string|null $nominal
     * @return int|null
     */
    private function modifyNominal(?string $nominal): ?int
    {
        return null !== $nominal ? (int)$nominal: null;
    }
}