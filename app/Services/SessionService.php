<?php
namespace App\Services;

class SessionService {
    public function startSecureSession(): void {
        session_start([
            'cookie_lifetime' => 86400, // 24h
            'cookie_secure' => true,
            'cookie_httponly' => true,
            'use_strict_mode' => true
        ]);
    }

    public function regenerateId(): void {
        session_regenerate_id(true);
    }

    public function destroy(): void {
        session_unset();
        session_destroy();
    }
}