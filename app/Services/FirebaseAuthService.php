<?php

namespace App\Services;

use Kreait\Firebase\Auth;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;

class FirebaseAuthService
{
    public function __construct(
        protected Auth $auth
    ) {}

    public function verifyIdToken(string $idToken): array
    {
        $verifiedToken = $this->auth->verifyIdToken($idToken);
        return $verifiedToken->claims()->all();
    }
}
