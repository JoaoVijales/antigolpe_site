<?php
namespace App\Services;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class LoggerService {
    private Logger $logger;

    public function __construct() {
        $this->logger = new Logger('main');
        $this->logger->pushHandler(
            new StreamHandler($_ENV['LOG_PATH'] ?? __DIR__.'/../../storage/logs/app.log')
        );
    }

    public function log(string $message, array $context = [], string $level = 'info'): void {
        $this->logger->log($level, $message, $context);
    }

    public function audit(string $action, array $metadata): void {
        $this->log("[AUDIT] $action", $metadata);
    }
}