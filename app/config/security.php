<?php
// Configurações de segurança do PHP
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 0);
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.use_strict_mode', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_lifetime', 0);
ini_set('session.gc_maxlifetime', 3600);

// Configurações de segurança gerais
ini_set('expose_php', 'Off');
ini_set('display_errors', 'On');
ini_set('display_startup_errors', 'On');
ini_set('log_errors', 'On');
ini_set('error_log', dirname(__DIR__, 2) . '/storage/logs/php_errors.log');
ini_set('allow_url_fopen', 'On');
ini_set('allow_url_include', 'Off');
ini_set('file_uploads', 'On');
ini_set('upload_max_filesize', '5M');
ini_set('post_max_size', '8M');
ini_set('max_execution_time', 30);
ini_set('max_input_time', 60);
ini_set('memory_limit', '128M');

// Configurações de segurança de sessão
session_start([
    'cookie_httponly' => true,
    'cookie_secure' => false,
    'cookie_samesite' => 'Lax',
    'use_strict_mode' => true,
    'use_only_cookies' => true,
    'cookie_lifetime' => 0,
    'gc_maxlifetime' => 3600
]);

// Função para regenerar ID de sessão periodicamente
function regenerateSession() {
    if (!isset($_SESSION['last_regeneration'])) {
        $_SESSION['last_regeneration'] = time();
    } else {
        $interval = 300; // 5 minutos
        if (time() - $_SESSION['last_regeneration'] > $interval) {
            session_regenerate_id(true);
            $_SESSION['last_regeneration'] = time();
        }
    }
}

// Função para limpar dados de sessão
function clearSessionData() {
    $_SESSION = array();
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    session_destroy();
}

// Função para validar token CSRF
function validateCSRFToken($token) {
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        http_response_code(403);
        die('Token CSRF inválido');
    }
}

// Função para gerar token CSRF
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Função para validar origem da requisição
function validateRequestOrigin() {
    $allowedOrigins = [
        'https://cyan-stinkbug-337154.hostingersite.com',
        'http://cyan-stinkbug-337154.hostingersite.com',
        'https://www.cyan-stinkbug-337154.hostingersite.com',
        'http://www.cyan-stinkbug-337154.hostingersite.com'
    ];

    if (isset($_SERVER['HTTP_ORIGIN'])) {
        if (!in_array($_SERVER['HTTP_ORIGIN'], $allowedOrigins)) {
            // Removido o bloqueio para debug
            // http_response_code(403);
            // die('Origem não permitida');
        }
        header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');
    }
}

// Função para validar método da requisição
function validateRequestMethod($allowedMethods = ['GET', 'POST']) {
    if (!in_array($_SERVER['REQUEST_METHOD'], $allowedMethods)) {
        http_response_code(405);
        die('Método não permitido');
    }
}

// Função para validar conteúdo da requisição
function validateContentType() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_SERVER['CONTENT_TYPE']) || 
            strpos($_SERVER['CONTENT_TYPE'], 'application/x-www-form-urlencoded') === false) {
            http_response_code(415);
            die('Tipo de conteúdo não suportado');
        }
    }
}

// Função para validar tamanho da requisição
function validateRequestSize() {
    $maxSize = 8 * 1024 * 1024; // 8MB
    if (isset($_SERVER['CONTENT_LENGTH']) && $_SERVER['CONTENT_LENGTH'] > $maxSize) {
        http_response_code(413);
        die('Requisição muito grande');
    }
}

// Função para validar IP
function validateIP() {
    $allowedIPs = [
        '127.0.0.1',
        '::1'
    ];

    if (!in_array($_SERVER['REMOTE_ADDR'], $allowedIPs)) {
        // Implementar lógica adicional de validação de IP
        // como verificação de proxy, geolocalização, etc.
    }
}

// Função para validar user agent
function validateUserAgent() {
    if (!isset($_SERVER['HTTP_USER_AGENT']) || 
        strlen($_SERVER['HTTP_USER_AGENT']) < 10) {
        http_response_code(403);
        die('User Agent inválido');
    }
}

// Função para validar referer
function validateReferer() {
    $allowedReferers = [
        'https://cyan-stinkbug-337154.hostingersite.com',
        'http://cyan-stinkbug-337154.hostingersite.com',
        'https://www.cyan-stinkbug-337154.hostingersite.com',
        'http://www.cyan-stinkbug-337154.hostingersite.com'
    ];

    if (isset($_SERVER['HTTP_REFERER'])) {
        $referer = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
        if (!in_array($referer, $allowedReferers)) {
            // Removido o bloqueio para debug
            // http_response_code(403);
            // die('Referer não permitido');
        }
    }
}

// Função para validar headers de segurança
function validateSecurityHeaders() {
    header('X-Frame-Options: SAMEORIGIN');
    header('X-XSS-Protection: 1; mode=block');
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: no-referrer-when-downgrade');
    header('Content-Security-Policy: default-src \'self\' \'unsafe-inline\' \'unsafe-eval\' data:; script-src \'self\' \'unsafe-inline\' \'unsafe-eval\'; style-src \'self\' \'unsafe-inline\';');
    // Removido HSTS para permitir HTTP
    // header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
}

// Inicializar validações de segurança
function initializeSecurity(): void {
    validateRequestOrigin();
    validateRequestMethod();
    validateContentType();
    validateRequestSize();
    validateIP();
    validateUserAgent();
    validateReferer();
    validateSecurityHeaders();
    regenerateSession();
}

// Chamar inicialização de segurança
initializeSecurity();
?> 