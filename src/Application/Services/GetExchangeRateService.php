<?php

namespace Application\Services;

use Application\DTO\GetExchangeRateRequestDTO;
use Application\DTO\GetExchangeRateResponse;
use Application\Entities\Currency;
use Application\Exceptions\CurrencyNotFoundException;
use Application\Exceptions\DataProviderError;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

readonly class GetExchangeRateService implements GetExchangeRateServiceInterface
{
    public function __construct(
        private EntityManagerInterface        $entityManager,
        private DownloadRatesServiceInterface $downloadRatesService,
    )
    {
    }

    /**
     * Returns exchange rate and difference with previous day
     *
     * @param GetExchangeRateRequestDTO $request
     * @return GetExchangeRateResponse
     * @throws CurrencyNotFoundException
     * @throws DataProviderError
     */
    public function execute(GetExchangeRateRequestDTO $request): GetExchangeRateResponse
    {
        $previousDay = clone $request->date;
        $previousDay->sub(new DateInterval('P1D'));

        $targetCurrency = $request->targetCurrencyCode != Currency::PIVOT_CURRENCY_CHARACTER_CODE ?
            $this->getCurrency($request->date, $request->targetCurrencyCode) :
            null;

        $sourceCurrency = $request->sourceCurrencyCode != Currency::PIVOT_CURRENCY_CHARACTER_CODE ?
            $this->getCurrency($request->date, $request->sourceCurrencyCode) :
            null;

        $targetCurrencyPreviousDay = $request->targetCurrencyCode != Currency::PIVOT_CURRENCY_CHARACTER_CODE ?
            $this->getCurrency($previousDay, $request->targetCurrencyCode) :
            null;

        $sourceCurrencyPreviousDay = $request->sourceCurrencyCode != Currency::PIVOT_CURRENCY_CHARACTER_CODE ?
            $this->getCurrency($previousDay, $request->sourceCurrencyCode) :
            null;

        $targetDayRate = $this->getExchangeRate($sourceCurrency, $targetCurrency);
        $previousDayRate = $this->getExchangeRate($sourceCurrencyPreviousDay, $targetCurrencyPreviousDay);
        $change = $this->getRatesDifference($targetDayRate, $previousDayRate);

        return new GetExchangeRateResponse(
            targetDayRate: number_format($targetDayRate,4),
            rateChange: $change
        );
    }

    /**
     * Find currency in storage. Execute download from external source if it wasn't found in storage.
     *
     * @param DateTime $date
     * @param string $characterCode
     * @return Currency
     * @throws CurrencyNotFoundException
     * @throws DataProviderError
     */
    private function getCurrency(DateTime $date, string $characterCode): Currency
    {
        $currency = $this->getCurrencyFromStorage($date, $characterCode);
        if (null === $currency) {
            $this->downloadRatesService->execute($date);
            $currency = $this->getCurrencyFromStorage($date, $characterCode);
            if (null === $currency) {
                throw new CurrencyNotFoundException(
                    sprintf("Currency rate with code %s not found for %s",
                        $characterCode,
                        $date->format("d-m-Y")
                    ));
            }
        }
        return $currency;
    }

    /**
     * Returns Currency object if it exists in storage
     * @param DateTime $date
     * @param string $characterCode
     * @return Currency|null
     */
    private function getCurrencyFromStorage(DateTime $date, string $characterCode): ?Currency
    {
        return $this->entityManager->getRepository(Currency::class)->findOneBy(
            [
                'date' => $date,
                'characterCode' => $characterCode
            ]
        );
    }

    /**
     * Calculates exchange rate
     * @param Currency|null $source
     * @param Currency|null $target
     * @return float
     */
    private function getExchangeRate(?Currency $source, ?Currency $target): float
    {
        $sourceToPivot = !is_null($source) ? $source->getValue() / $source->getNominal() : 1;
        $targetToPivot = !is_null($target) ? $target->getValue() / $target->getNominal() : 1;
        return $targetToPivot / $sourceToPivot;
    }

    /**
     * Calculates difference between rates
     * @param float $targetDayRate
     * @param float $previousDayRate
     * @return string
     */
    private function getRatesDifference(float $targetDayRate, float $previousDayRate): string
    {
        return sprintf("%+f", number_format($targetDayRate - $previousDayRate, 4));
    }
}