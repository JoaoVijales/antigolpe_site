<?php
namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Exception\FirebaseException;

class FirebaseAuthService {
    private Auth $auth;
    private string $projectId;

    public function __construct() {
        $this->initializeFirebase();
    }

    private function initializeFirebase(): void {
        try {
            $serviceAccount = json_decode(
                $_ENV['FIREBASE_ADMIN_SDK_SECRET_KEY'],
                true
            );

            $this->auth = (new Factory)
                ->withServiceAccount($serviceAccount)
                ->withProjectId($_ENV['FIREBASE_PROJECT_ID'])
                ->createAuth();

        } catch (\InvalidArgumentException $e) {
            throw new \RuntimeException('Erro de configuração do Firebase: ' . $e->getMessage());
        }
    }

    // Métodos de API
    public function createUser(string $email, string $password) {
        return $this->auth->createUser([
            'email' => $email,
            'password' => $password,
            'emailVerified' => false
        ]);
    }

    public function verifyIdToken(string $token) {
        return $this->auth->verifyIdToken($token);
    }

    public function createSessionCookie(string $idToken, int $expiresIn = 3600) {
        return $this->auth->createSessionCookie($idToken, $expiresIn);
    }

    public function getUser(string $uid) {
        return $this->auth->getUser($uid);
    }
    
    public function deleteUser(string $uid) {
        return $this->auth->deleteUser($uid);
    }
    
    public function verifyPassword(string $inputPassword, string $storedHash): bool {
        return password_verify($inputPassword, $storedHash);
    }
}