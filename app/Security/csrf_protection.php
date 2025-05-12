<?php
namespace App\Security;

use Exception;

/**
 * Sistema de Proteção CSRF
 * 
 * Implementa proteção contra ataques Cross-Site Request Forgery (CSRF)
 * através de tokens únicos por sessão.
 * 
 * @package Security
 * @author Sistema de Segurança
 * @version 1.0.0
 */

session_start();

/**
 * Gera um token CSRF único para a sessão atual
 * 
 * Se não existir um token na sessão, gera um novo usando random_bytes.
 * O token é armazenado na sessão para validação posterior.
 * 
 * @return string Token CSRF em formato hexadecimal
 */
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Valida um token CSRF recebido
 * 
 * Compara o token recebido com o token armazenado na sessão.
 * Se não houver correspondência, retorna erro 403.
 * 
 * @param string $token Token CSRF a ser validado
 * @return bool true se o token for válido
 * @throws Exception Se o token for inválido
 */
function validateCSRFToken($token) {
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        http_response_code(403);
        die('CSRF token validation failed');
    }
    return true;
}

/**
 * Regenera o token CSRF após uso
 * 
 * Gera um novo token CSRF e o armazena na sessão.
 * Deve ser chamado após cada operação sensível para maior segurança.
 * 
 * @return void
 */
function regenerateCSRFToken() {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?> 