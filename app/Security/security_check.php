<?php
// Verificar se o arquivo está sendo acessado diretamente
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('Acesso direto não permitido');
}

// Função para verificar a segurança do ambiente
// TODO: Refatorar - A lógica de verificação de segurança nesta função (headers, permissões, .env) se sobrepõe a App\Services\SecurityDashboard. Considere mover esta lógica para lá e usar os métodos da classe de serviço.
function checkSecurity() {
    $checks = array();
    
    // Verificar HTTPS
    $checks['https'] = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
    
    // Verificar headers de segurança
    $checks['security_headers'] = array(
        'X-Content-Type-Options' => false,
        'X-Frame-Options' => false,
        'X-XSS-Protection' => false,
        'Strict-Transport-Security' => false
    );
    
    $headers = headers_list();
    foreach ($headers as $header) {
        foreach ($checks['security_headers'] as $key => $value) {
            if (stripos($header, $key) !== false) {
                $checks['security_headers'][$key] = true;
            }
        }
    }
    
    // Verificar permissões de arquivos
    $checks['file_permissions'] = array(
        '.env' => substr(sprintf('%o', fileperms('.env')), -4) === '0600',
        'config.php' => substr(sprintf('%o', fileperms('config.php')), -4) === '0600',
        'config.js' => substr(sprintf('%o', fileperms('config.js')), -4) === '0644'
    );
    
    // Verificar se o .env existe e está protegido
    $checks['env_protection'] = !file_exists('.env') || !is_readable('.env');
    
    return $checks;
}

// Função para gerar relatório de segurança
// TODO: Refatorar - Esta função gera um relatório baseado nas verificações locais. Pode ser consolidada ou usar dados de App\Services\SecurityDashboard se a lógica de verificação for movida.
function generateSecurityReport() {
    $checks = checkSecurity();
    $report = array();
    
    // Verificar HTTPS
    if (!$checks['https']) {
        $report[] = 'AVISO: HTTPS não está ativo';
    }
    
    // Verificar headers de segurança
    foreach ($checks['security_headers'] as $header => $enabled) {
        if (!$enabled) {
            $report[] = "AVISO: Header de segurança {$header} não está configurado";
        }
    }
    
    // Verificar permissões de arquivos
    foreach ($checks['file_permissions'] as $file => $correct) {
        if (!$correct) {
            $report[] = "AVISO: Permissões incorretas para {$file}";
        }
    }
    
    // Verificar proteção do .env
    if (!$checks['env_protection']) {
        $report[] = 'ERRO CRÍTICO: Arquivo .env está acessível';
    }
    
    return $report;
}

// Executar verificação de segurança
$securityReport = generateSecurityReport();

// Se houver problemas críticos, registrar no log
if (!empty($securityReport)) {
    error_log('Problemas de segurança detectados: ' . implode(', ', $securityReport));
}
?> 