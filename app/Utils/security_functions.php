<?php
// Funções de sanitização
function sanitizeOutput($data) {
    if (is_array($data)) {
        return array_map('sanitizeOutput', $data);
    }
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

function sanitizeInput($data) {
    if (is_array($data)) {
        return array_map('sanitizeInput', $data);
    }
    return strip_tags(trim($data));
}

// Função para validar email
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Função para validar URL
function validateURL($url) {
    return filter_var($url, FILTER_VALIDATE_URL);
}

// Função para validar número
function validateNumber($number) {
    return is_numeric($number) && $number > 0;
}

// Função para validar data
function validateDate($date) {
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

// Função para validar token JWT
function validateJWT($token) {
    try {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return false;
        }
        $payload = json_decode(base64_decode($parts[1]), true);
        if (!$payload || !isset($payload['exp'])) {
            return false;
        }
        return $payload['exp'] > time();
    } catch (Exception $e) {
        return false;
    }
}

// Função para validar senha forte
function validateStrongPassword($password) {
    return strlen($password) >= 8 &&
           preg_match('/[A-Z]/', $password) &&
           preg_match('/[a-z]/', $password) &&
           preg_match('/[0-9]/', $password) &&
           preg_match('/[^A-Za-z0-9]/', $password);
}

// Função para validar IP
function validateIP($ip) {
    return filter_var($ip, FILTER_VALIDATE_IP);
}

// Função para validar domínio
function validateDomain($domain) {
    return preg_match('/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i', $domain);
}

// Função para validar número de telefone
function validatePhone($phone) {
    return preg_match('/^\+?[1-9]\d{1,14}$/', $phone);
}

// Função para validar CPF
function validateCPF($cpf) {
    $cpf = preg_replace('/[^0-9]/', '', $cpf);
    if (strlen($cpf) != 11) {
        return false;
    }
    if (preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }
    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) {
            return false;
        }
    }
    return true;
}

// Função para validar CNPJ
function validateCNPJ($cnpj) {
    $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
    if (strlen($cnpj) != 14) {
        return false;
    }
    if (preg_match('/(\d)\1{13}/', $cnpj)) {
        return false;
    }
    for ($i = 0, $j = 5, $sum = 0; $i < 12; $i++) {
        $sum += $cnpj[$i] * $j;
        $j = ($j == 2) ? 9 : $j - 1;
    }
    $rest = $sum % 11;
    if ($cnpj[12] != ($rest < 2 ? 0 : 11 - $rest)) {
        return false;
    }
    for ($i = 0, $j = 6, $sum = 0; $i < 13; $i++) {
        $sum += $cnpj[$i] * $j;
        $j = ($j == 2) ? 9 : $j - 1;
    }
    $rest = $sum % 11;
    return $cnpj[13] == ($rest < 2 ? 0 : 11 - $rest);
}
?> 