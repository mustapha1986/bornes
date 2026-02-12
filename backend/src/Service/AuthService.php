<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthService
{
    public function __construct(
        private readonly string $authToken
    ) {}

    public function assertAuthorized(Request $request): void
    {
        $header = $request->headers->get('Authorization');
        if (!$header || !str_starts_with($header, 'Bearer ')) {
            throw new UnauthorizedHttpException('Bearer', 'Missing authorization token.');
        }

        $token = substr($header, 7);
        if ($token !== $this->authToken) {
            throw new UnauthorizedHttpException('Bearer', 'Invalid authorization token.');
        }
    }
}