<?php
declare(strict_types=1);

// Linhas temporárias para exibição de erro - remova ou comente em produção
// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);


/**
 * Ponto de entrada principal da aplicação (Front Controller)
 */

require __DIR__ . '/../vendor/autoload.php';

use App\Utils\Container;
use App\Utils\HttpResponse;
use FastRoute\Dispatcher;
use App\Middlewares\CorsMiddleware;

// Configuração inicial
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

// Debug temporário: Verificar se APP_ENV foi carregado
// echo "APP_ENV carregado: " . ($_ENV['APP_ENV'] ?? 'Não Definido') . "<br>"; // Manter o debug comentado

define('APP_ENV', isset($_ENV['APP_ENV']) ? $_ENV['APP_ENV'] : 'production');

// Configuração de ambiente
ini_set('display_errors', (APP_ENV === 'development') ? '1' : '0');
ini_set('display_startup_errors', (APP_ENV === 'development') ? '1' : '0');
error_reporting((APP_ENV === 'development') ? E_ALL : 0);

// Middlewares globais
CorsMiddleware::handle();

// Container de dependências
$container = Container::getInstance();

try {
    // Configuração do roteador
    $dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
        require __DIR__ . '/app/Config/routes.php';
    });

    // Processamento da requisição
    $httpMethod = $_SERVER['REQUEST_METHOD'];
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    // --- Adicionar lógica para remover o base path (como no default.php) ---
    $basePath = dirname($_SERVER['SCRIPT_NAME']);
    // Ensure the base path ends with a slash if it's not just '/'
    if ($basePath !== '/' && substr($basePath, -1) !== '/') {
        $basePath .= '/';
    }
    // Remove the base path from the URI
    if (substr($uri, 0, strlen($basePath)) === $basePath) {
        $uri = substr($uri, strlen($basePath));
    }
    // Ensure the URI starts with a slash, even if it's now empty (root)
    if ($uri === '' || $uri === false) {
        $uri = '/';
    }
    // -------------------------------------------------------------------

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
    $logger = $container->get('logger');
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