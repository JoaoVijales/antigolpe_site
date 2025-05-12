<?php
namespace App\Services;

class SecurityMonitor {
    private $logger;
    private $redis;
    private $suspiciousPatterns = [
        'sql_injection' => [
            'pattern' => '/\b(UNION|SELECT|INSERT|UPDATE|DELETE|DROP|ALTER|EXEC|DECLARE)\b/i',
            'threshold' => 3
        ],
        'xss' => [
            'pattern' => '/<script|javascript:|on\w+\s*=|data:\s*text\/html/i',
            'threshold' => 3
        ],
        'path_traversal' => [
            'pattern' => '/\.\.\/|\.\.\\|%2e%2e%2f|%252e%252e%252f/i',
            'threshold' => 3
        ],
        'command_injection' => [
            'pattern' => '/\b(system|exec|shell_exec|passthru|eval|assert)\s*\(/i',
            'threshold' => 2
        ]
    ];

    public function __construct(Logger $logger) {
        $this->logger = $logger;
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
            $this->logger->error("Erro ao conectar com Redis: " . $e->getMessage());
        }
    }

    public function monitorRequest() {
        $this->checkRequestHeaders();
        $this->checkRequestParameters();
        $this->checkRequestPatterns();
        $this->checkRequestFrequency();
    }

    private function checkRequestHeaders() {
        $suspiciousHeaders = [
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'HTTP_CLIENT_IP',
            'HTTP_X_CLIENT_IP',
            'HTTP_X_CLUSTER_CLIENT_IP'
        ];

        foreach ($suspiciousHeaders as $header) {
            if (isset($_SERVER[$header])) {
                $this->logger->warning("Header suspeito detectado", [
                    'header' => $header,
                    'value' => $_SERVER[$header]
                ]);
            }
        }
    }

    private function checkRequestParameters() {
        $parameters = array_merge($_GET, $_POST);
        foreach ($parameters as $key => $value) {
            if (is_string($value)) {
                foreach ($this->suspiciousPatterns as $type => $config) {
                    if (preg_match($config['pattern'], $value)) {
                        $this->incrementSuspiciousCounter($type);
                        $this->logger->warning("Padrão suspeito detectado", [
                            'type' => $type,
                            'parameter' => $key,
                            'value' => $value
                        ]);
                    }
                }
            }
        }
    }

    private function checkRequestPatterns() {
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        $method = $_SERVER['REQUEST_METHOD'] ?? '';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';

        // Verificar padrões suspeitos na URI
        foreach ($this->suspiciousPatterns as $type => $config) {
            if (preg_match($config['pattern'], $uri)) {
                $this->incrementSuspiciousCounter($type);
                $this->logger->warning("Padrão suspeito na URI", [
                    'type' => $type,
                    'uri' => $uri
                ]);
            }
        }

        // Verificar métodos HTTP suspeitos
        if (!in_array($method, ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'])) {
            $this->logger->warning("Método HTTP suspeito", [
                'method' => $method
            ]);
        }

        // Verificar User-Agent suspeito
        if (strlen($userAgent) < 10 || preg_match('/bot|crawler|spider/i', $userAgent)) {
            $this->logger->info("User-Agent suspeito", [
                'user_agent' => $userAgent
            ]);
        }
    }

    private function checkRequestFrequency() {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $key = "request_frequency:{$ip}";
        
        $requests = $this->redis->incr($key);
        $this->redis->expire($key, 60); // Expira em 1 minuto

        if ($requests > 100) { // Mais de 100 requisições por minuto
            $this->logger->warning("Alta frequência de requisições", [
                'ip' => $ip,
                'requests' => $requests
            ]);
        }
    }

    private function incrementSuspiciousCounter($type) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $key = "suspicious:{$type}:{$ip}";
        
        $count = $this->redis->incr($key);
        $this->redis->expire($key, 3600); // Expira em 1 hora

        if ($count >= $this->suspiciousPatterns[$type]['threshold']) {
            $this->logger->critical("Limite de tentativas suspeitas excedido", [
                'type' => $type,
                'ip' => $ip,
                'count' => $count
            ]);
            
            // Bloquear IP temporariamente
            $this->blockIP($ip);
        }
    }

    private function blockIP($ip) {
        $key = "blocked_ip:{$ip}";
        $this->redis->setex($key, 3600, 1); // Bloqueia por 1 hora
        
        $this->logger->critical("IP bloqueado", [
            'ip' => $ip,
            'duration' => 3600
        ]);
    }

    public function isIPBlocked($ip) {
        $key = "blocked_ip:{$ip}";
        return $this->redis->exists($key);
    }

    public function getSuspiciousCount($type, $ip) {
        $key = "suspicious:{$type}:{$ip}";
        return $this->redis->get($key) ?: 0;
    }

    public function resetSuspiciousCount($type, $ip) {
        $key = "suspicious:{$type}:{$ip}";
        $this->redis->del($key);
    }

    public function unblockIP($ip) {
        $key = "blocked_ip:{$ip}";
        $this->redis->del($key);
        
        $this->logger->info("IP desbloqueado", [
            'ip' => $ip
        ]);
    }
}
?> 