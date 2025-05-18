<?php
/**
 * Ponto de entrada principal da aplicação (Front Controller)
 */

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Utils\Container;
use App\Utils\HttpResponse;
use FastRoute\Dispatcher;
use App\Middlewares\CorsMiddleware;

// Configuração inicial
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

define('APP_ENV', $_ENV['APP_ENV'] ?? 'production');

// Configuração de ambiente
ini_set('display_errors', (APP_ENV === 'development') ? '1' : '0');
ini_set('display_startup_errors', (APP_ENV === 'development') ? '1' : '0');
error_reporting((APP_ENV === 'development') ? E_ALL : 0);

try {
    // Middlewares globais
    CorsMiddleware::handle();

    // Container de dependências
    $container = Container::getInstance();
    
    // Configuração do roteador
    $dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
        require __DIR__ . '/../app/config/routes.php';
    });

    // Processamento da requisição
    $httpMethod = $_SERVER['REQUEST_METHOD'];
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
    
    switch ($routeInfo[0]) {
        case Dispatcher::FOUND:
            $handler = $routeInfo[1];
            $vars = $routeInfo[2];
            
            // Resolução de dependências
            [$controllerClass, $method] = $handler;
            $controller = $container->resolve($controllerClass);
            
            // Execução do método do controller
            $response = $controller->$method($vars);
            $response->send();
            break;

        case Dispatcher::NOT_FOUND:
            (new HttpResponse(404, ['error' => 'Recurso não encontrado']))->send();
            break;

        case Dispatcher::METHOD_NOT_ALLOWED:
            (new HttpResponse(405, ['error' => 'Método não permitido']))->send();
            break;
    }

} catch (\Throwable $e) {
    // Tratamento centralizado de erros
    $logger = Container::get('logger');
    $logger->error("Falha crítica: {$e->getMessage()}", [
        'trace' => $e->getTraceAsString(),
        'arquivo' => $e->getFile(),
        'linha' => $e->getLine()
    ]);

    // Temporário: Exibir erro no navegador em ambiente de desenvolvimento
    if (APP_ENV === 'development') {
        echo "<h1>Erro 500</h1>";
        echo "<p>Ocorreu um erro interno no servidor:</p>";
        echo "<p>Detalhes do erro:</p>";
        echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
        echo "<p>Rastreamento da pilha:</p>";
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    }

    $responseData = APP_ENV === 'development'
        ? ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]
        : ['error' => 'Ocorreu um erro inesperado'];

    (new HttpResponse(500, $responseData))->send();
}