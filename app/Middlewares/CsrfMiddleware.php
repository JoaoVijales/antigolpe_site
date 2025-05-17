<?php
namespace App\Middlewares;

use App\Utils\HttpResponse;

class CsrfMiddleware {
    public function handle(): void {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            HttpResponse::json(403, ['error' => 'Token CSRF inválido']);
        }
    }
}