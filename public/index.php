<?php
declare(strict_types=1);

/**
 * Ponto de entrada principal da aplicação (Front Controller)
 */

require __DIR__ . '/../vendor/autoload.php';

use App\Utils\Container;
use App\Utils\HttpResponse;
use FastRoute\Dispatcher;
use App\Middlewares\CorsMiddleware;
use App\Utils\HtmlResponse; // Certificar que HtmlResponse está importado para verificações, se necessário.

// Configuração inicial
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

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
        // Carregar e chamar a closure de definição de rotas
        $routesDefinition = require __DIR__ . '/../app/Config/routes.php';

        // Verificar se o arquivo de rotas retornou um callable (closure)
        if (is_callable($routesDefinition)) {
            $routesDefinition($r); // CORREÇÃO: Chamar a closure passando o RouteCollector
        } else {
            // Tratar erro: routes.php não retornou o que era esperado
            // Em um ambiente de produção, você pode querer lançar uma exceção ou logar isso de forma mais robusta
            if (APP_ENV === 'development') {
                 echo "<h1>Erro de Configuração</h1><p>O arquivo de rotas não retornou uma função callable.</p>";
            }
            // Dependendo da sua lógica, pode ser necessário parar a execução ou adicionar uma rota de fallback
        }
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
            
            // Se o handler for uma closure (embora com a estrutura de routes.php não deva acontecer mais)
            if ($handler instanceof Closure) {
                 // Tratar closures se necessário - ajuste conforme sua necessidade
                 // Por agora, assumimos que handlers de routes.php sempre retornam [Classe, Método].
                 // Se tiverem closures em routes.php, a lógica abaixo para controllers precisará ser ajustada/copiada.
                 echo "Erro: Handler closure encontrada onde [Classe, Método] era esperado."; // Log de erro
                 (new HttpResponse(500, ['error' => 'Internal Server Error', 'details' => 'Unexpected closure handler']))->send();

            } else {
                // Lógica para controllers baseados em array [Classe, Método]
                // Resolução de dependências
                [$controllerClass, $method] = $handler;

                // Verificar se a classe do controller existe antes de tentar resolver
                if (!class_exists($controllerClass)) {
                     echo "Erro: Classe do Controller não encontrada: " . $controllerClass; // Log de erro
                     (new HttpResponse(500, ['error' => 'Internal Server Error', 'details' => 'Controller class not found']))->send();
                     break; // Sair do switch após enviar a resposta de erro
                }

                $controller = $container->resolve($controllerClass);

                // Verificar se o método existe no controller resolvido
                 if (!method_exists($controller, $method)) {
                     echo "Erro: Método do Controller não encontrado: " . $method . " na classe " . $controllerClass; // Log de erro
                      (new HttpResponse(500, ['error' => 'Internal Server Error', 'details' => 'Controller method not found']))->send();
                     break; // Sair do switch após enviar a resposta de erro
                 }

                // Execução do método do controller
                $response = $controller->$method($vars);

                // Verificar se o retorno é um objeto de resposta válido (possui o método send())
                if (!(is_object($response) && method_exists($response, 'send'))) { // CORREÇÃO: Verificar se é objeto e tem método send()
                     echo "Erro: O retorno do Controller não é um objeto de resposta válido com método send()."; // Log de erro
                      (new HttpResponse(500, ['error' => 'Internal Server Error', 'details' => 'Controller did not return a valid response object']))->send();
                     break; // Sair do switch após enviar a resposta de erro
                }

                $response->send();
            } // Fim do if handler instanceof Closure

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
    // Tentar obter o logger do container. Pode falhar se o container não estiver configurado corretamente.
    try {
        $logger = $container->get('logger');
        // Reativar a linha de log
        $logger->error("Falha crítica: {$e->getMessage()}", [
            'trace' => $e->getTraceAsString(),
            'arquivo' => $e->getFile(),
            'linha' => $e->getLine()
        ]);
    } catch (\Throwable $loggerError) {
        // Se falhar ao obter ou usar o logger, logar a falha no log de sistema ou stderr
        error_log("ERRO CRITICO (Logger Falhou): " . $e->getMessage() . " em " . $e->getFile() . ":" . $e->getLine());
        error_log("Trace original: " . $e->getTraceAsString());
        error_log("Falha no Logger: " . $loggerError->getMessage());
    }

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

     // Enviar a resposta de erro HTTP
     // Nota: Usar (new HttpResponse(...))->send() é preferível se a classe HttpResponse gerencia headers/output buffers.
     // Se não, um fallback manual como http_response_code(500); echo json_encode($responseData); pode ser necessário.
     // Mantendo a chamada original que assume HttpResponse funciona:
     (new HttpResponse(500, $responseData))->send();
}