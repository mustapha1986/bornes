<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    public function __construct(
        private readonly string $authUser,
        private readonly string $authPassword,
        private readonly string $authToken
    ) {}

    #[Route('/api/auth/login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $payload = json_decode($request->getContent(), true) ?? [];
        $username = $payload['username'] ?? '';
        $password = $payload['password'] ?? '';

        if ($username !== $this->authUser || $password !== $this->authPassword) {
            return $this->json(['error' => 'Identifiants invalides.'], 401);
        }

        return $this->json([
            'token' => $this->authToken,
            'user' => [
                'username' => $this->authUser,
            ],
        ]);
    }
}