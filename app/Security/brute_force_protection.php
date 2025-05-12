<?php
namespace App\Security;

use Redis;
use Exception;

/**
 * Classe BruteForceProtection
 * 
 * Implementa proteção contra ataques de força bruta em tentativas de login.
 * Utiliza Redis para armazenamento em cache das tentativas e bloqueios.
 * 
 * @package Security
 * @author Sistema de Segurança
 * @version 1.0.0
 */
class BruteForceProtection {
    /** @var int Número máximo de tentativas permitidas antes do bloqueio */
    private $maxAttempts = 5;

    /** @var int Tempo de bloqueio em segundos (15 minutos) */
    private $lockoutTime = 900;

    /** @var Redis Instância do cliente Redis */
    private $redis;

    /**
     * Construtor da classe
     * 
     * Inicializa a conexão com o Redis usando as configurações do ambiente.
     * 
     * @throws Exception Se houver erro na conexão com Redis
     */
    public function __construct() {
        try {
            $this->redis = new Redis();
            $this->redis->connect(
                getenv('REDIS_HOST', 'localhost'),
                getenv('REDIS_PORT', 6379)
            );
            if (getenv('REDIS_PASSWORD')) {
                $this->redis->auth(getenv('REDIS_PASSWORD'));
            }
        } catch (Exception $e) {
            error_log("Erro ao conectar com Redis: " . $e->getMessage());
            throw new Exception("Erro ao inicializar proteção contra força bruta");
        }
    }

    /**
     * Verifica se um identificador (IP ou usuário) está bloqueado
     * 
     * @param string $identifier IP ou nome de usuário para verificar
     * @return bool true se não estiver bloqueado, false caso contrário
     * @throws Exception Se o identificador estiver bloqueado
     */
    public function checkAttempts($identifier) {
        $key = "login_attempts:{$identifier}";
        $attempts = $this->redis->get($key);

        if ($attempts >= $this->maxAttempts) {
            $lockoutKey = "lockout:{$identifier}";
            $lockoutTime = $this->redis->ttl($lockoutKey);

            if ($lockoutTime <= 0) {
                $this->redis->del($key);
                $this->redis->del($lockoutKey);
                return true;
            }

            throw new Exception("Muitas tentativas. Tente novamente em " . ceil($lockoutTime / 60) . " minutos.");
        }

        return true;
    }

    /**
     * Registra uma tentativa de login
     * 
     * @param string $identifier IP ou nome de usuário
     * @param bool $success true se o login foi bem-sucedido, false caso contrário
     */
    public function logAttempt($identifier, $success) {
        $key = "login_attempts:{$identifier}";
        
        if ($success) {
            $this->redis->del($key);
            $this->redis->del("lockout:{$identifier}");
            return;
        }

        $attempts = $this->redis->incr($key);
        $this->redis->expire($key, 3600); // Expira em 1 hora

        if ($attempts >= $this->maxAttempts) {
            $lockoutKey = "lockout:{$identifier}";
            $this->redis->setex($lockoutKey, $this->lockoutTime, 1);
            
            // Notificar administrador
            $this->notifyAdmin($identifier);
        }
    }

    /**
     * Notifica o administrador sobre tentativas suspeitas
     * 
     * @param string $identifier IP ou nome de usuário que gerou o alerta
     */
    private function notifyAdmin($identifier) {
        $message = "Alerta de segurança: Múltiplas tentativas de login detectadas para {$identifier}";
        error_log($message);
        
        // Aqui você pode implementar notificações adicionais
        // como email, SMS, etc.
    }

    /**
     * Retorna o número de tentativas restantes para um identificador
     * 
     * @param string $identifier IP ou nome de usuário
     * @return int Número de tentativas restantes
     */
    public function getRemainingAttempts($identifier) {
        $key = "login_attempts:{$identifier}";
        $attempts = $this->redis->get($key);
        return max(0, $this->maxAttempts - $attempts);
    }

    /**
     * Retorna o tempo restante de bloqueio para um identificador
     * 
     * @param string $identifier IP ou nome de usuário
     * @return int Tempo restante em segundos
     */
    public function getLockoutTime($identifier) {
        $lockoutKey = "lockout:{$identifier}";
        return $this->redis->ttl($lockoutKey);
    }

    /**
     * Reseta as tentativas e bloqueio para um identificador
     * 
     * @param string $identifier IP ou nome de usuário
     */
    public function resetAttempts($identifier) {
        $key = "login_attempts:{$identifier}";
        $lockoutKey = "lockout:{$identifier}";
        
        $this->redis->del($key);
        $this->redis->del($lockoutKey);
    }
}
?> 