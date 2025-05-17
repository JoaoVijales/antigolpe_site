<?php
namespace App\Middlewares;

use App\Utils\HttpResponse;

class AuthMiddleware {
    public function handle(): void {
        $excludedRoutes = ['/login', '/api/auth/login'];
        if (!in_array($_SERVER['REQUEST_URI'], $excludedRoutes) && !isset($_SESSION['user'])) {
            HttpResponse::redirect('/login');
        }
    }
}