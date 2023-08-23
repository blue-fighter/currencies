<?php

namespace Application\Handlers;

use Application\Messages\ObtainDayRateMessage;
use Application\Services\DownloadRatesServiceInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

/**
 * Get the exchange rate for a specific date and save it
 */
#[AsMessageHandler]
readonly class GetDayRateHandler
{

    public function __construct(
        private DownloadRatesServiceInterface $downloadRatesService,
    )
    {
    }

    public function __invoke(ObtainDayRateMessage $message): void
    {
        $this->downloadRatesService->execute($message->getTargetDay());
    }

}