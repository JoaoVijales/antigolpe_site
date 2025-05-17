<?php
declare(strict_types=1);

use App\Services\FirebaseAuthService;
use App\Services\LoggerService;
use App\Services\ValidationService;
use App\Services\SessionService;

return [
    'firebase.auth' => function() {
        return new FirebaseAuthService();
    },
    
    'logger' => function() {
        $logger = new LoggerService();
        $logger->log('Logger inicializado');
        return $logger;
    },
    
    'validator' => function() {
        return new ValidationService();
    },
    
    'session' => function() {
        $session = new SessionService();
        $session->startSecureSession();
        return $session;
    },
    
    'view' => function() {
        return new \App\Utils\View();
    }
];