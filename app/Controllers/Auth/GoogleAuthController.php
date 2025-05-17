<?php
namespace App\Controllers\Auth;

use App\Services\FirebaseAuthService;
use App\Utils\HttpResponse;
use App\Services\ValidationService;

class GoogleAuthController {
    private $authService;
    private $validator;

    public function __construct(
        FirebaseAuthService $authService,
        ValidationService $validator
    ) {
        $this->authService = $authService;
        $this->validator = $validator;
    }

    public function authenticate(array $requestData): HttpResponse {
        if (!$this->validator->validate($requestData, [
            'idToken' => ['required']
        ])) {
            return new HttpResponse(400, [
                'error' => $this->validator->getFirstError()
            ]);
        }

        try {
            $verifiedToken = $this->authService->verifyIdToken($requestData['idToken']);
            $user = $this->authService->getUser($verifiedToken->getClaim('sub'));
            
            return new HttpResponse(200, [
                'uid' => $user->uid,
                'email' => $user->email,
                'name' => $user->displayName
            ]);

        } catch (\Exception $e) {
            return new HttpResponse(401, ['error' => 'Falha na autenticação Google']);
        }
    }
}