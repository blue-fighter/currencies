<?php

namespace Application\Handlers;

use Application\Messages\CollectDataMessage;
use Application\Messages\ObtainDayRateMessage;
use DateInterval;
use DatePeriod;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class CollectDataHandler
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    /**
     * @param CollectDataMessage $message
     * @return void
     */
    public function __invoke(CollectDataMessage $message): void
    {
        $one_day_interval = new DateInterval('P1D');
        $period = new DatePeriod(
            $message->getFirstDay(),
            $one_day_interval,
            $message->getLastDay(),
            DatePeriod::INCLUDE_END_DATE
        );
        foreach ($period as $date) {
            $this->messageBus->dispatch(new ObtainDayRateMessage($date));
        }
    }
}