<?php

namespace Application\Services;

use Application\DTO\GetExchangeRateRequestDTO;
use Application\DTO\GetExchangeRateResponse;
use Application\Exceptions\CurrencyNotFoundException;
use Application\Exceptions\DataProviderError;

interface GetExchangeRateServiceInterface
{
    /**
     * @param GetExchangeRateRequestDTO $request
     * @return GetExchangeRateResponse
     * @throws CurrencyNotFoundException
     * @throws DataProviderError
     */
    public function execute(GetExchangeRateRequestDTO $request): GetExchangeRateResponse;
}