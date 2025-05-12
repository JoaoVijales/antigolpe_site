<?php
// Ativa exibição de erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "Iniciando instalação...<br>";

// Verifica se o composer.phar existe
if (!file_exists('composer.phar')) {
    echo "Baixando Composer...<br>";
    copy('https://getcomposer.org/installer', 'composer-setup.php');
    
    if (file_exists('composer-setup.php')) {
        include 'composer-setup.php';
        unlink('composer-setup.php');
    } else {
        die("Erro ao baixar o Composer");
    }
}

echo "Instalando dependências...<br>";

// Executa o Composer
$output = [];
$returnVar = 0;
exec('php composer.phar install', $output, $returnVar);

// Mostra o resultado
echo "<pre>";
print_r($output);
echo "</pre>";

if ($returnVar === 0) {
    echo "Instalação concluída com sucesso!";
} else {
    echo "Erro durante a instalação.";
} 