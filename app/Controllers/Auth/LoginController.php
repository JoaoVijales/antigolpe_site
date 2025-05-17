<?php
namespace App\Controllers\Auth;

use App\Services\FirebaseAuthService;
use App\Services\SessionService;
use App\Services\ValidationService;
use App\Utils\HttpResponse;
use Kreait\Firebase\Exception\Auth\UserNotFound;
use Kreait\Firebase\Exception\Auth\InvalidPassword;

class LoginController extends AuthController {
    private $authService;

    public function __construct(
        SessionService $session,
        ValidationService $validator,
        FirebaseAuthService $authService
    ) {
        parent::__construct($session, $validator);
        $this->authService = $authService;
    }

    public function login(array $data): HttpResponse {
        // Validação dos dados de entrada
        if (!$this->validator->validate($data, [
            'email' => ['required', 'email'],
            'password' => ['required', 'min_length:8']
        ])) {
            return new HttpResponse(400, [
                'view' => 'components/auth/login_form',
                'data' => [
                    'errors' => $this->validator->getErrors(),
                    'csrf_token' => $_SESSION['csrf_token']
                ]
            ]);
        }

        try {
            // Autenticação com Firebase
            $user = $this->authService->getUserByEmail($data['email']);
            $this->authService->verifyPassword($data['password'], $user->passwordHash);
            
            // Atualização da sessão
            $this->session->regenerateId();
            $this->session->set('user', [
                'uid' => $user->uid,
                'email' => $user->email
            ]);

            // Geração do token JWT
            $token = $this->authService->createCustomToken($user->uid);
            
            return $this->respondWithToken((string)$token);

        } catch (UserNotFound $e) {
            return new HttpResponse(404, [
                'success' => false,
                'error' => 'Usuário não encontrado'
            ]);
        } catch (InvalidPassword $e) {
            return new HttpResponse(401, [
                'success' => false,
                'error' => 'Senha incorreta'
            ]);
        } catch (\Throwable $e) {
            return new HttpResponse(500, [
                'success' => false,
                'error' => 'Erro interno no servidor'
            ]);
        }
    }
}