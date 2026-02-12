<?php

namespace App\Service;

use App\Document\Station;

class StatsService
{
    public function build(array $stations): array
    {
        $stats = [
            'total' => count($stations),
            Station::STATUS_AVAILABLE => 0,
            Station::STATUS_CHARGING => 0,
            Station::STATUS_OFFLINE => 0,
        ];

        foreach ($stations as $station) {
            $status = $station->getStatus();
            if (isset($stats[$status])) {
                $stats[$status]++;
            }
        }

        return $stats;
    }
}