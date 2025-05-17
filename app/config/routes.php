<?php
use FastRoute\RouteCollector;

return function (RouteCollector $r) {
    // Rotas da API
    $r->addGroup('/api', function (RouteCollector $r) {
        $r->addGroup('/auth', function (RouteCollector $r) {
            $r->addRoute('POST', '/register', [App\Controllers\Auth\EmailAuthController::class, 'register']);
            $r->addRoute('POST', '/login', [App\Controllers\Auth\LoginController::class, 'login']);
            $r->addRoute('POST', '/google', [App\Controllers\Auth\GoogleAuthController::class, 'authenticate']);
        });
    });

    // Rotas Web
    $r->addGroup('', function (RouteCollector $r) {
        $r->addRoute('GET', '/', [App\Controllers\HomeController::class, 'index']);
        $r->addRoute('GET', '/dashboard', [App\Controllers\DashboardController::class, 'securityDashboard']);
        $r->addRoute('GET', '/404', [App\Controllers\HomeController::class, 'notFound']);
    });
};