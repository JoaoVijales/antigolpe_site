<?php
/**
 * Ponto de entrada principal da aplicação
 */

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Define o diretório raiz da aplicação
define('ROOT_PATH', dirname(__DIR__));

// Ativa exibição de erros para debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define modo de debug
define('APP_DEBUG', true);

// Carrega as configurações
require_once ROOT_PATH . '/app/config/database.php';
require_once ROOT_PATH . '/app/config/security.php';

use App\Services\Logger;
use App\Utils\Database;
use App\Services\SecurityDashboard;
use App\Utils\PageStats;
use App\Services\GoogleAnalytics;
use App\Security\FileUploadSecurity;

try {
    // Inicializa o logger
    $logger = new Logger();

    // Inicializa o banco de dados
    $db = new Database();

    // Inicializa o Google Analytics
    $ga = new GoogleAnalytics($logger);

    // Inicializa o PageStats
    $pageStats = new PageStats($db, $logger);

    // Registra a visualização da página atual
    $pageStats->recordPageView($_SERVER['REQUEST_URI']);

    // Inicializa o dashboard de segurança
    $securityDashboard = new SecurityDashboard($logger, $db);

    // Inicializa proteções de segurança
    $fileUpload = new FileUploadSecurity();

    // Roteamento básico
    $route = $_SERVER['REQUEST_URI'];

    // Roteamento
    switch ($route) {
        case '/':
            // Página inicial
            ob_start();
            require ROOT_PATH . '/resources/views/index.html';
            $indexHtmlContent = ob_get_clean();

            $gaTrackingCode = $ga->getTrackingCode();
            $headEndPosition = strpos($indexHtmlContent, '</head>');
            if ($headEndPosition !== false) {
                $outputContent = substr_replace($indexHtmlContent, $gaTrackingCode . '</head>', $headEndPosition, strlen('</head>'));
                echo $outputContent;
            } else {
                // Se </head> não for encontrado, apenas inclua o conteúdo (menos ideal)
                echo $indexHtmlContent;
                echo $gaTrackingCode;
            }
            break;
            
        case '/dashboard':
            // Dashboard de segurança
            $pageStatsData = $pageStats->getPageStats();
            $totalStats = $pageStats->getTotalStats();
            $metrics = $securityDashboard->getSecurityMetrics();

            // Captura o output do arquivo PHP do dashboard
            ob_start();
            require ROOT_PATH . '/resources/views/dashboard/security.php';
            $dashboardHtmlContent = ob_get_clean();

            $gaTrackingCode = $ga->getTrackingCode();
            $headEndPosition = strpos($dashboardHtmlContent, '</head>');

            if ($headEndPosition !== false) {
                $outputContent = substr_replace($dashboardHtmlContent, $gaTrackingCode . '</head>', $headEndPosition, strlen('</head>'));
                echo $outputContent;
            } else {
                // Se </head> não for encontrado, apenas inclua o conteúdo (menos ideal)
                echo $dashboardHtmlContent;
                echo $gaTrackingCode;
            }
            break;
            
        default:
            http_response_code(404);
            require ROOT_PATH . '/resources/views/404.php';
            break;
    }
} catch (\Exception $e) {
    // Log do erro
    $logger->error("Erro na aplicação: " . $e->getMessage());
    
    // Exibe mensagem de erro detalhada em modo debug
    if (APP_DEBUG) {
        echo '<h1>Erro na Aplicação</h1>';
        echo '<p>Mensagem: ' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '<p>Arquivo: ' . htmlspecialchars($e->getFile()) . '</p>';
        echo '<p>Linha: ' . $e->getLine() . '</p>';
        echo '<h2>Stack Trace:</h2>';
        echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
    } else {
        echo '<h1>Erro na Aplicação</h1>';
        echo '<p>Ocorreu um erro inesperado. Por favor, tente novamente mais tarde.</p>';
    }
} 