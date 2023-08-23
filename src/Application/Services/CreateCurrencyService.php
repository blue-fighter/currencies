<?php

namespace Application\Services;

use Application\DTO\CreateCurrencyRequestDTO;
use Application\Factories\CurrencyFactoryInterface;
use Doctrine\ORM\EntityManagerInterface;

readonly class CreateCurrencyService implements CreateCurrencyServiceInterface
{
    /**
     * @param CurrencyFactoryInterface $currencyFactory
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        private CurrencyFactoryInterface $currencyFactory,
        private EntityManagerInterface   $entityManager
    )
    {
    }

    /**
     * @param CreateCurrencyRequestDTO $request
     * @return void
     */
    public function execute(CreateCurrencyRequestDTO $request): void
    {
        $currency = $this->currencyFactory->create($request);
        $this->entityManager->persist($currency);
    }
}