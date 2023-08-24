<?php

namespace Application\Services;

use Application\Exceptions\DataProviderError;
use Application\Factories\CreateCurrencyRequestFactoryInterface;
use CBRBundle\Exceptions\CBRException;
use CBRBundle\Services\GetDayRateServiceInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Throwable;

readonly class DownloadRatesService implements DownloadRatesServiceInterface
{
    public function __construct(
        private GetDayRateServiceInterface            $getDayRateService,
        private CreateCurrencyServiceInterface        $createCurrencyService,
        private CreateCurrencyRequestFactoryInterface $createCurrencyRequestFactory,
        private EntityManagerInterface                $entityManager
    )
    {
    }

    /**
     * @param DateTime $date
     * @return void
     * @throws DataProviderError
     */
    public function execute(DateTime $date): void
    {
        try{
            $rates = $this->getDayRateService->execute($date);
        } catch (CBRException $e){
            throw new DataProviderError(message: $e->getMessage(), previous: $e);
        }
        foreach ($rates as $rate) {
            $createCurrencyRequest = $this->createCurrencyRequestFactory->createFromCBRRate($rate);
            try {
                $this->createCurrencyService->execute($createCurrencyRequest);
                $this->entityManager->flush();
            } catch (Throwable){
                // No processing required
            }

        }

    }
}