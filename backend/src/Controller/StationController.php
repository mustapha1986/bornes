<?php

namespace App\Controller;

use App\Document\Station;
use App\Service\AuthService;
use App\Service\MercurePublisher;
use App\Service\StatsService;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class StationController extends AbstractController
{
    public function __construct(
        private readonly DocumentManager $documentManager,
        private readonly AuthService $authService,
        private readonly MercurePublisher $mercurePublisher,
        private readonly StatsService $statsService
    ) {}

    #[Route('/api/stations', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $this->authService->assertAuthorized($request);
        $stations = $this->documentManager->getRepository(Station::class)->findAll();
        return $this->json(['stations' => array_map(fn (Station $s) => $s->toArray(), $stations)]);
    }

    #[Route('/api/stations', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $this->authService->assertAuthorized($request);
        $payload = json_decode($request->getContent(), true) ?? [];

        $x = (int) ($payload['x'] ?? 0);
        $y = (int) ($payload['y'] ?? 0);

        $station = new Station($x, $y);
        $this->documentManager->persist($station);
        $this->documentManager->flush();

        $this->broadcast();

        return $this->json(['station' => $station->toArray()], 201);
    }

    #[Route('/api/stations/{id}', methods: ['PATCH'])]
    public function update(string $id, Request $request): JsonResponse
    {
        $this->authService->assertAuthorized($request);
        $payload = json_decode($request->getContent(), true) ?? [];

        $station = $this->documentManager->find(Station::class, $id);
        if (!$station) {
            return $this->json(['error' => 'Station introuvable.'], 404);
        }

        if (isset($payload['status'])) {
            $station->setStatus($payload['status']);
        }

        $this->documentManager->flush();
        $this->broadcast();

        return $this->json(['station' => $station->toArray()]);
    }

    #[Route('/api/stations/{id}', methods: ['DELETE'])]
    public function delete(string $id, Request $request): JsonResponse
    {
        $this->authService->assertAuthorized($request);
        $station = $this->documentManager->find(Station::class, $id);
        if (!$station) {
            return $this->json(['error' => 'Station introuvable.'], 404);
        }

        $this->documentManager->remove($station);
        $this->documentManager->flush();

        $this->broadcast();

        return $this->json(['status' => 'deleted']);
    }

    #[Route('/api/stats', methods: ['GET'])]
    public function stats(Request $request): JsonResponse
    {
        $this->authService->assertAuthorized($request);
        $stations = $this->documentManager->getRepository(Station::class)->findAll();
        return $this->json(['stats' => $this->statsService->build($stations)]);
    }

    private function broadcast(): void
    {
        $stations = $this->documentManager->getRepository(Station::class)->findAll();
        $stationPayload = array_map(fn (Station $s) => $s->toArray(), $stations);
        $stats = $this->statsService->build($stations);

        $this->mercurePublisher->publishStations($stationPayload);
        $this->mercurePublisher->publishStats($stats);
    }
}