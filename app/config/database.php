<?php
// Configurações do Banco de Dados
$envFile = __DIR__ . '/../../.env';
$config = [];

if (file_exists($envFile)) {
    $config = parse_ini_file($envFile);
}

// Configurações padrão caso o .env não exista
$config = array_merge([
    'DB_HOST' => 'localhost',
    'DB_NAME' => 'u906925595_security',
    'DB_USER' => 'u906925595_security',
    'DB_PASS' => ''
], $config);

// Define as variáveis de ambiente
putenv('DB_HOST=' . $config['DB_HOST']);
putenv('DB_NAME=' . $config['DB_NAME']);
putenv('DB_USER=' . $config['DB_USER']);
putenv('DB_PASS=' . $config['DB_PASS']);

// Retorna as configurações do banco de dados
return [
    'host' => $config['DB_HOST'],
    'dbname' => $config['DB_NAME'],
    'username' => $config['DB_USER'],
    'password' => $config['DB_PASS'],
    'charset' => 'utf8mb4'
];
?> 