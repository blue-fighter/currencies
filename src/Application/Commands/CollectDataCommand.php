<?php

namespace Application\Commands;

use Application\Messages\CollectDataMessage;
use DateInterval;
use DateTime;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'app:collect-data',
    description: 'Collect exchange rates for the last 180 days',
)]
class CollectDataCommand extends Command
{
    const DAY_INTERVAL = 180;

    public function __construct(private readonly MessageBusInterface $messageBus)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $last_day = new DateTime();
        $first_day = clone $last_day;
        try {
            $first_day->sub(new DateInterval(sprintf("P%sD", self::DAY_INTERVAL)));
            $confirmation = $io->confirm(sprintf(
                    "Application will collect exchange rates from %s to %s",
                    $first_day->format("Y-m-d"),
                    $last_day->format("Y-m-d")
                )
            );
            if ($confirmation) {
                $this->messageBus->dispatch(new CollectDataMessage($first_day, $last_day));
            } else{
                $io->info("Execution cancelled");
            }

        } catch (\Throwable $e) {
            $io->error($e->getMessage());
            return Command::FAILURE;
        }
        return Command::SUCCESS;
    }
}
