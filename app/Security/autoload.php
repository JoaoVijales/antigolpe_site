<?php
/**
 * Autoloader para classes de segurança
 * 
 * @package Security
 * @author Sistema de Segurança
 * @version 1.0.0
 */

spl_autoload_register(function ($class) {
    // Mapeamento de classes para arquivos
    $classMap = [
        'BruteForceProtection' => __DIR__ . '/brute_force_protection.php',
        'FileUploadSecurity' => __DIR__ . '/file_upload_security.php'
    ];

    // Verifica se a classe está no mapa
    if (isset($classMap[$class])) {
        require_once $classMap[$class];
        return true;
    }

    return false;
});

// Carrega funções CSRF
require_once __DIR__ . '/csrf_protection.php';
?> 