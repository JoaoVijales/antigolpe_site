<?php
namespace App\Controllers\Auth;

use App\Services\SessionService;
use App\Services\ValidationService;
use App\Utils\HttpResponse;

abstract class AuthController {
    protected SessionService $session;
    protected ValidationService $validator;
    
    public function __construct(
        SessionService $session,
        ValidationService $validator
    ) {
        $this->session = $session;
        $this->validator = $validator;
    }

    protected function respondWithToken(string $token): HttpResponse {
        return new HttpResponse(200, [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => 3600
        ]);
    }
}