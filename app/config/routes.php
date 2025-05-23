<?php
use FastRoute\RouteCollector;

return function (RouteCollector $r) {
    // Rotas da API
    $r->addGroup('/api', function (RouteCollector $r) {
        $r->addGroup('/auth', function (RouteCollector $r) {
        });
    });

    // Rotas Web
    $r->addGroup('', function (RouteCollector $r) {
        $r->addRoute('GET', '/', [App\Controllers\HomeController::class, 'index']);
        $r->addRoute('GET', '/dashboard', [App\Controllers\DashboardController::class, 'index']);
        $r->addRoute('GET', '/404', [App\Controllers\HomeController::class, 'notFound']);
        $r->addRoute('GET', '/dashboard/verify-whatsapp', [App\Controllers\DashboardController::class, 'verify_phone']);
        $r->addRoute('GET', '/dashboard/register-phone', [App\Controllers\DashboardController::class, 'register_phone']);
        $r->addRoute('GET', '/dashboard/config', [App\Controllers\DashboardController::class, 'config']);
    });
};