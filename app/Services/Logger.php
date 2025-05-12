<?php
namespace App\Services;

class Logger {
    private $logDir;
    private $logLevels = [
        'DEBUG' => 0,
        'INFO' => 1,
        'WARNING' => 2,
        'ERROR' => 3,
        'CRITICAL' => 4
    ];
    private $currentLevel;
    private $logFile;
    private $maxFileSize = 5242880; // 5MB
    private $maxFiles = 5;

    public function __construct($logDir = 'logs/', $level = 'INFO') {
        $this->logDir = rtrim($logDir, '/') . '/';
        $this->currentLevel = $level;
        
        if (!is_dir($this->logDir)) {
            mkdir($this->logDir, 0755, true);
        }

        $this->logFile = $this->logDir . date('Y-m-d') . '.log';
        $this->rotateLogs();
    }

    private function rotateLogs() {
        if (file_exists($this->logFile) && filesize($this->logFile) > $this->maxFileSize) {
            $files = glob($this->logDir . '*.log');
            rsort($files);

            foreach ($files as $index => $file) {
                if ($index >= $this->maxFiles - 1) {
                    unlink($file);
                } else {
                    $newName = $this->logDir . date('Y-m-d') . '_' . ($index + 1) . '.log';
                    rename($file, $newName);
                }
            }
        }
    }

    public function log($level, $message, $context = []) {
        if ($this->logLevels[$level] < $this->logLevels[$this->currentLevel]) {
            return;
        }

        $timestamp = date('Y-m-d H:i:s');
        $logEntry = [
            'timestamp' => $timestamp,
            'level' => $level,
            'message' => $message,
            'context' => $context,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'request_uri' => $_SERVER['REQUEST_URI'] ?? 'unknown',
            'request_method' => $_SERVER['REQUEST_METHOD'] ?? 'unknown'
        ];

        $logLine = json_encode($logEntry) . "\n";
        file_put_contents($this->logFile, $logLine, FILE_APPEND | LOCK_EX);

        if ($level === 'CRITICAL' || $level === 'ERROR') {
            $this->notifyAdmins($logEntry);
        }
    }

    private function notifyAdmins($logEntry) {
        $message = "ALERTA DE SEGURANÇA\n";
        $message .= "Nível: {$logEntry['level']}\n";
        $message .= "Mensagem: {$logEntry['message']}\n";
        $message .= "IP: {$logEntry['ip']}\n";
        $message .= "URI: {$logEntry['request_uri']}\n";
        $message .= "Método: {$logEntry['request_method']}\n";
        $message .= "Contexto: " . json_encode($logEntry['context'], JSON_PRETTY_PRINT);

        error_log($message);

        // Aqui você pode implementar notificações adicionais
        // como email, SMS, Slack, etc.
    }

    public function debug($message, $context = []) {
        $this->log('DEBUG', $message, $context);
    }

    public function info($message, $context = []) {
        $this->log('INFO', $message, $context);
    }

    public function warning($message, $context = []) {
        $this->log('WARNING', $message, $context);
    }

    public function error($message, $context = []) {
        $this->log('ERROR', $message, $context);
    }

    public function critical($message, $context = []) {
        $this->log('CRITICAL', $message, $context);
    }

    public function setLogLevel($level) {
        if (isset($this->logLevels[$level])) {
            $this->currentLevel = $level;
        }
    }

    public function getLogLevel() {
        return $this->currentLevel;
    }

    public function getLogFile() {
        return $this->logFile;
    }

    public function setMaxFileSize($size) {
        $this->maxFileSize = $size;
    }

    public function setMaxFiles($count) {
        $this->maxFiles = $count;
    }
}
?> 