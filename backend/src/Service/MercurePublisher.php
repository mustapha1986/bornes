<?php

namespace App\Service;

use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

class MercurePublisher
{
    public function __construct(
        private readonly HubInterface $hub,
        private readonly string $mercureTopicStations,
        private readonly string $mercureTopicStats
    ) {}

    public function publishStations(array $stations): void
    {
        $this->hub->publish(new Update(
            $this->mercureTopicStations,
            json_encode(['stations' => $stations], JSON_THROW_ON_ERROR)
        ));
    }

    public function publishStats(array $stats): void
    {
        $this->hub->publish(new Update(
            $this->mercureTopicStats,
            json_encode(['stats' => $stats], JSON_THROW_ON_ERROR)
        ));
    }
}