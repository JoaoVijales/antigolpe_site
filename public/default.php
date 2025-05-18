<?php
/**
 * Arquivo default.php - Ponto de entrada alternativo
 */

// Ativa exibição de erros para debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define o diretório raiz da aplicação
define('ROOT_PATH', dirname(__DIR__));

// Verifica se o Composer está instalado
if (!file_exists(ROOT_PATH . '/vendor/autoload.php')) {
    die("O Composer não está instalado. Por favor, acesse <a href='/install.php'>install.php</a> para instalar as dependências.");
}

// Debug - Verifica se os arquivos existem
$requiredFiles = [
    ROOT_PATH . '/vendor/autoload.php',
    ROOT_PATH . '/app/config/database.php',
    ROOT_PATH . '/app/config/security.php'
];

foreach ($requiredFiles as $file) {
    if (!file_exists($file)) {
        die("Arquivo não encontrado: " . $file);
    }
}

// Carrega o autoloader do Composer
require_once ROOT_PATH . '/vendor/autoload.php';

// Carrega as configurações
require_once ROOT_PATH . '/app/config/database.php';
require_once ROOT_PATH . '/app/config/security.php';

// Inicializa o logger
$logger = new \App\Services\Logger(ROOT_PATH . '/storage/logs');

// Inicializa o dashboard de segurança
$dashboard = new \App\Services\SecurityDashboard($logger, new \App\Utils\Database());

// Roteamento básico
$request = $_SERVER['REQUEST_URI'];
$basePath = dirname($_SERVER['SCRIPT_NAME']);

// Debug - Mostra informações da requisição
echo "Request URI: " . $request . "<br>";
echo "Base Path: " . $basePath . "<br>";
echo "Script Name: " . $_SERVER['SCRIPT_NAME'] . "<br>";

// Remove o caminho base da URL
$request = str_replace($basePath, '', $request);

// Debug - Mostra a requisição processada
echo "Processed Request: " . $request . "<br>";

// Roteamento
switch ($request) {
    case '/':
    case '/dashboard':
    case '':
        $viewFile = ROOT_PATH . '/resources/views/dashboard/security.php';
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            die("Arquivo de view não encontrado: " . $viewFile);
        }
        break;
    default:
        http_response_code(404);
        $viewFile = ROOT_PATH . '/resources/views/404.php';
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            die("Página não encontrada: " . $request);
        }
        break;
} 









"""

                     ########%                   
                 ################%               
            %############%############%          
        %#############%     %#############%      
        #########                 ########%      
        #####                         #####      
        #####                  ##%    #####      
        #####                ######   ####%      
        #####              ########   #####      
        #####    #####   ########     #####      
        #####%  %######%#######      %####%      
        @#####    ###########        #####       
         ######     #######%        ######       
          #####%     %####         ######        
          %######                %######         
            #######            ########          
             %#######        %#######%           
               %########% %########%             
                 #################               
                    %##########                  
                                                 
                                                 
"""                                                               