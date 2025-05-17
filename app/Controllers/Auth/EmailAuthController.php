<?php
// app/Controllers/Auth/EmailAuthController.php
namespace App\Controllers\Auth;

use App\Services\FirebaseAuthService;
use App\Services\ValidationService;
use App\Utils\HttpResponse;

class EmailAuthController {
    private $authService;
    private $validator;

    public function __construct(
        FirebaseAuthService $authService,
        ValidationService $validator
    ) {
        $this->authService = $authService;
        $this->validator = $validator;
    }

    public function register(array $requestData): HttpResponse {
        if (!$this->validator->validate($requestData, [
            'email' => ['required', 'email'],
            'password' => ['required', 'password']
        ])) {
            return new HttpResponse(400, [
                'error' => $this->validator->getFirstError()
            ]);
        }

        try {
            $user = $this->authService->createUser(
                $requestData['email'],
                $requestData['password']
            );
            
            return new HttpResponse(201, [
                'uid' => $user->uid,
                'email' => $user->email,
                'csrf_token' => $this->session->get('csrf_token')
            ]);

        } catch (\Exception $e) {
            return new HttpResponse(500, ['error' => $e->getMessage()]);
        }
    }
}