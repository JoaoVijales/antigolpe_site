<?php
namespace App\Utils;

use PDO;
use PDOException;
use Exception;
use App\Services\Logger;

class DatabaseSetup {
    private $db;
    private $logger;

    public function __construct(Database $db, Logger $logger) {
        $this->db = $db;
        $this->logger = $logger;
    }

    public function setup() {
        try {
            // Verifica e cria a tabela de logs de segurança
            $this->createTable('security_logs', "
                CREATE TABLE IF NOT EXISTS security_logs (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    type VARCHAR(50) NOT NULL,
                    severity ENUM('low', 'medium', 'high', 'critical') NOT NULL,
                    message TEXT NOT NULL,
                    details JSON,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )
            ");

            // Verifica e cria a tabela de IPs bloqueados
            $this->createTable('blocked_ips', "
                CREATE TABLE IF NOT EXISTS blocked_ips (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    ip VARCHAR(45) NOT NULL,
                    reason VARCHAR(255) NOT NULL,
                    block_duration INT NOT NULL DEFAULT 15,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    expires_at TIMESTAMP GENERATED ALWAYS AS (DATE_ADD(created_at, INTERVAL block_duration MINUTE)) STORED,
                    UNIQUE KEY unique_ip (ip)
                )
            ");

            // Verifica e cria a tabela de atividades suspeitas
            $this->createTable('suspicious_activities', "
                CREATE TABLE IF NOT EXISTS suspicious_activities (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    type VARCHAR(50) NOT NULL,
                    details JSON,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    INDEX idx_type (type),
                    INDEX idx_created_at (created_at)
                )
            ");

            // Verifica e cria a tabela de estatísticas de acesso às páginas
            $this->createTable('page_views', "
                CREATE TABLE IF NOT EXISTS page_views (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    page_path VARCHAR(255) NOT NULL,
                    visitor_ip VARCHAR(45) NOT NULL,
                    user_agent VARCHAR(255),
                    referer VARCHAR(255),
                    request_method VARCHAR(10) NOT NULL DEFAULT 'GET',
                    full_url VARCHAR(512) NOT NULL,
                    query_string VARCHAR(255),
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    INDEX idx_page_path (page_path),
                    INDEX idx_created_at (created_at),
                    INDEX idx_visitor_ip (visitor_ip)
                )
            ");

            $this->logger->info("Configuração do banco de dados concluída com sucesso");
            return true;
        } catch (\Exception $e) {
            $this->logger->error("Erro ao configurar banco de dados: " . $e->getMessage());
            throw $e;
        }
    }

    private function createTable($tableName, $sql) {
        try {
            $this->db->query($sql);
            $this->logger->info("Tabela {$tableName} criada ou verificada com sucesso");
        } catch (\Exception $e) {
            $this->logger->error("Erro ao criar tabela {$tableName}: " . $e->getMessage());
            throw $e;
        }
    }
} 