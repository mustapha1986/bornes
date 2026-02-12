<?php

namespace App\Command;

use App\Document\Station;
use App\Service\MercurePublisher;
use App\Service\StatsService;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:simulate-stations')]
class SimulateStationsCommand extends Command
{
    public function __construct(
        private readonly DocumentManager $documentManager,
        private readonly MercurePublisher $publisher,
        private readonly StatsService $statsService
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $statuses = [
            Station::STATUS_AVAILABLE,
            Station::STATUS_CHARGING,
            Station::STATUS_OFFLINE,
        ];

        $stations = $this->documentManager->getRepository(Station::class)->findAll();
        foreach ($stations as $station) {
            if (random_int(0, 100) < 35) {
                $station->setStatus($statuses[array_rand($statuses)]);
            }
        }

        $this->documentManager->flush();

        $payload = array_map(fn (Station $s) => $s->toArray(), $stations);
        $this->publisher->publishStations($payload);
        $this->publisher->publishStats($this->statsService->build($stations));

        $output->writeln('Stations updated.');
        return Command::SUCCESS;
    }
}