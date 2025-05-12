<?php

namespace App\Utils;

use App\Services\Logger;
use PDO;

class PageStats {
    private $db;
    private $logger;

    public function __construct(Database $db, Logger $logger) {
        $this->db = $db;
        $this->logger = $logger;
    }

    public function recordPageView($pagePath) {
        try {
            // Obtém informações do visitante
            $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
            $referer = $_SERVER['HTTP_REFERER'] ?? '';
            $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
            $protocol = $_SERVER['SERVER_PROTOCOL'] ?? 'HTTP/1.1';
            $host = $_SERVER['HTTP_HOST'] ?? '';
            $queryString = $_SERVER['QUERY_STRING'] ?? '';
            $fullUrl = $protocol . '://' . $host . $pagePath . ($queryString ? '?' . $queryString : '');

            // Registra a visualização
            $sql = "INSERT INTO page_views (
                        page_path, 
                        visitor_ip, 
                        user_agent, 
                        referer,
                        request_method,
                        full_url,
                        query_string
                    ) VALUES (?, ?, ?, ?, ?, ?, ?)";
            
            $this->db->query($sql, [
                $pagePath,
                $ip,
                $userAgent,
                $referer,
                $method,
                $fullUrl,
                $queryString
            ]);

            $this->logger->info("Visualização registrada: {$pagePath} - IP: {$ip}");
            return true;
        } catch (\Exception $e) {
            $this->logger->error("Erro ao registrar visualização da página: " . $e->getMessage());
            return false;
        }
    }

    public function getPageStats($period = 'today') {
        try {
            $dateCondition = '';
            $params = [];

            switch ($period) {
                case 'today':
                    $dateCondition = 'DATE(created_at) = CURDATE()';
                    break;
                case 'week':
                    $dateCondition = 'created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)';
                    break;
                case 'month':
                    $dateCondition = 'created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)';
                    break;
                default:
                    $dateCondition = 'DATE(created_at) = CURDATE()';
            }

            $sql = "SELECT 
                        page_path,
                        COUNT(*) as total_views,
                        COUNT(DISTINCT visitor_ip) as unique_visitors,
                        MAX(created_at) as last_visit
                    FROM page_views 
                    WHERE {$dateCondition}
                    GROUP BY page_path
                    ORDER BY total_views DESC";

            return $this->db->query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            $this->logger->error("Erro ao obter estatísticas de páginas: " . $e->getMessage());
            return [];
        }
    }

    public function getTotalStats($period = 'today') {
        try {
            $dateCondition = '';
            $params = [];

            switch ($period) {
                case 'today':
                    $dateCondition = 'DATE(created_at) = CURDATE()';
                    break;
                case 'week':
                    $dateCondition = 'created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)';
                    break;
                case 'month':
                    $dateCondition = 'created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)';
                    break;
                default:
                    $dateCondition = 'DATE(created_at) = CURDATE()';
            }

            $sql = "SELECT 
                        COUNT(*) as total_views,
                        COUNT(DISTINCT visitor_ip) as unique_visitors,
                        COUNT(DISTINCT page_path) as total_pages,
                        COUNT(DISTINCT DATE(created_at)) as total_days
                    FROM page_views 
                    WHERE {$dateCondition}";

            return $this->db->query($sql, $params)->fetch(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            $this->logger->error("Erro ao obter estatísticas totais: " . $e->getMessage());
            return [
                'total_views' => 0,
                'unique_visitors' => 0,
                'total_pages' => 0,
                'total_days' => 0
            ];
        }
    }
} 