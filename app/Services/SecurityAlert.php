<?php
class SecurityAlert {
    private $logger;
    private $redis;
    private $alertChannels = [];
    private $alertThresholds = [
        'login_failure' => 5,
        'suspicious_activity' => 3,
        'file_upload' => 2,
        'sql_injection' => 1,
        'xss_attempt' => 1
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

    public function addAlertChannel($channel, $config) {
        $this->alertChannels[$channel] = $config;
    }

    public function triggerAlert($type, $data) {
        $this->logger->warning("Alerta de segurança", [
            'type' => $type,
            'data' => $data
        ]);

        $key = "alert_count:{$type}";
        $count = $this->redis->incr($key);
        $this->redis->expire($key, 3600); // Expira em 1 hora

        if ($count >= ($this->alertThresholds[$type] ?? 1)) {
            $this->sendAlert($type, $data);
            $this->redis->del($key);
        }
    }

    private function sendAlert($type, $data) {
        foreach ($this->alertChannels as $channel => $config) {
            try {
                switch ($channel) {
                    case 'email':
                        $this->sendEmailAlert($type, $data, $config);
                        break;
                    case 'slack':
                        $this->sendSlackAlert($type, $data, $config);
                        break;
                    case 'sms':
                        $this->sendSMSAlert($type, $data, $config);
                        break;
                    case 'webhook':
                        $this->sendWebhookAlert($type, $data, $config);
                        break;
                }
            } catch (Exception $e) {
                $this->logger->error("Erro ao enviar alerta", [
                    'channel' => $channel,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    private function sendEmailAlert($type, $data, $config) {
        $subject = "Alerta de Segurança: {$type}";
        $message = $this->formatAlertMessage($type, $data);
        
        $headers = [
            'From' => $config['from'] ?? 'security@seusite.com',
            'Reply-To' => $config['reply_to'] ?? 'security@seusite.com',
            'X-Mailer' => 'PHP/' . phpversion(),
            'Content-Type' => 'text/html; charset=UTF-8'
        ];

        foreach ($config['recipients'] as $recipient) {
            mail($recipient, $subject, $message, $headers);
        }
    }

    private function sendSlackAlert($type, $data, $config) {
        $message = [
            'text' => "🚨 *Alerta de Segurança*",
            'attachments' => [
                [
                    'color' => '#ff0000',
                    'title' => $type,
                    'text' => $this->formatAlertMessage($type, $data),
                    'ts' => time()
                ]
            ]
        ];

        $ch = curl_init($config['webhook_url']);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);
    }

    private function sendSMSAlert($type, $data, $config) {
        $message = $this->formatAlertMessage($type, $data);
        
        foreach ($config['recipients'] as $recipient) {
            // Implementar integração com serviço de SMS
            // Exemplo: Twilio, Nexmo, etc.
        }
    }

    private function sendWebhookAlert($type, $data, $config) {
        $message = [
            'type' => $type,
            'data' => $data,
            'timestamp' => time()
        ];

        $ch = curl_init($config['webhook_url']);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'X-Security-Token: ' . $config['token']
        ]);
        curl_exec($ch);
        curl_close($ch);
    }

    private function formatAlertMessage($type, $data) {
        $message = "<h2>Alerta de Segurança</h2>";
        $message .= "<p><strong>Tipo:</strong> {$type}</p>";
        $message .= "<p><strong>Data/Hora:</strong> " . date('Y-m-d H:i:s') . "</p>";
        $message .= "<p><strong>IP:</strong> " . ($data['ip'] ?? 'unknown') . "</p>";
        $message .= "<p><strong>Detalhes:</strong></p>";
        $message .= "<pre>" . json_encode($data, JSON_PRETTY_PRINT) . "</pre>";
        
        return $message;
    }

    public function setAlertThreshold($type, $threshold) {
        $this->alertThresholds[$type] = $threshold;
    }

    public function getAlertThreshold($type) {
        return $this->alertThresholds[$type] ?? 1;
    }

    public function resetAlertCount($type) {
        $key = "alert_count:{$type}";
        $this->redis->del($key);
    }
}
?> 