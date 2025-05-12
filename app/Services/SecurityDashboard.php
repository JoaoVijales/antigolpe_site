<?php
namespace App\Services;

use App\Utils\Database;
use Exception;

class SecurityDashboard {
    private $logger;
    private $db;

    public function __construct(Logger $logger, Database $db) {
        $this->logger = $logger;
        $this->db = $db;
    }

    public function getSecurityMetrics() {
        return [
            'total_alerts' => $this->getTotalAlerts(),
            'active_threats' => $this->getActiveThreats(),
            'blocked_ips' => $this->getBlockedIPs(),
            'failed_logins' => $this->getFailedLogins(),
            'suspicious_activities' => $this->getSuspiciousActivities()
        ];
    }

    private function getTotalAlerts() {
        $query = "SELECT COUNT(*) as total FROM security_logs";
        $result = $this->db->query($query);
        return $result->fetch()['total'] ?? 0;
    }

    private function getActiveThreats() {
        $query = "SELECT type, COUNT(*) as count, MAX(created_at) as last_seen 
                 FROM security_logs 
                 WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                 GROUP BY type";
        
        $result = $this->db->query($query);
        $threats = [];
        
        while ($row = $result->fetch()) {
            $threats[] = [
                'type' => $row['type'],
                'count' => $row['count'],
                'last_seen' => $row['last_seen']
            ];
        }
        
        return $threats;
    }

    private function getBlockedIPs() {
        $query = "SELECT ip, reason, created_at as blocked_at, 
                        DATE_ADD(created_at, INTERVAL block_duration MINUTE) as expires_at
                 FROM blocked_ips 
                 WHERE expires_at > NOW()";
        
        $result = $this->db->query($query);
        $blocked = [];
        
        while ($row = $result->fetch()) {
            $blocked[] = [
                'ip' => $row['ip'],
                'reason' => $row['reason'],
                'blocked_at' => $row['blocked_at'],
                'expires_at' => $row['expires_at']
            ];
        }
        
        return $blocked;
    }

    private function getFailedLogins() {
        $query = "SELECT username, COUNT(*) as attempts, 
                        MAX(created_at) as last_attempt, ip
                 FROM login_attempts 
                 WHERE success = 0 
                 AND created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                 GROUP BY username, ip";
        
        $result = $this->db->query($query);
        $failed = [];
        
        while ($row = $result->fetch()) {
            $failed[] = [
                'username' => $row['username'],
                'attempts' => $row['attempts'],
                'last_attempt' => $row['last_attempt'],
                'ip' => $row['ip']
            ];
        }
        
        return $failed;
    }

    private function getSuspiciousActivities() {
        $query = "SELECT type, COUNT(*) as count, 
                        MAX(created_at) as last_seen, details
                 FROM suspicious_activities 
                 WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                 GROUP BY type, details";
        
        $result = $this->db->query($query);
        $activities = [];
        
        while ($row = $result->fetch()) {
            $activities[] = [
                'type' => $row['type'],
                'count' => $row['count'],
                'last_seen' => $row['last_seen'],
                'details' => $row['details']
            ];
        }
        
        return $activities;
    }

    public function getSecurityLogs($limit = 100, $offset = 0) {
        $query = "SELECT * FROM security_logs ORDER BY created_at DESC LIMIT ? OFFSET ?";
        return $this->db->query($query, [$limit, $offset]);
    }

    public function getSecurityStats($period = '24h') {
        $stats = [
            'alerts_by_type' => $this->getAlertsByType($period),
            'alerts_by_severity' => $this->getAlertsBySeverity($period),
            'alerts_timeline' => $this->getAlertsTimeline($period)
        ];
        
        return $stats;
    }

    private function getAlertsByType($period) {
        $query = "SELECT type, COUNT(*) as count 
                 FROM security_logs 
                 WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? HOUR)
                 GROUP BY type";
        
        $hours = $this->getPeriodHours($period);
        return $this->db->query($query, [$hours]);
    }

    private function getAlertsBySeverity($period) {
        $query = "SELECT severity, COUNT(*) as count 
                 FROM security_logs 
                 WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? HOUR)
                 GROUP BY severity";
        
        $hours = $this->getPeriodHours($period);
        return $this->db->query($query, [$hours]);
    }

    private function getAlertsTimeline($period) {
        $query = "SELECT DATE_FORMAT(created_at, '%Y-%m-%d %H:00') as hour,
                        COUNT(*) as count
                 FROM security_logs
                 WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? HOUR)
                 GROUP BY hour
                 ORDER BY hour";
        
        $hours = $this->getPeriodHours($period);
        return $this->db->query($query, [$hours]);
    }

    private function getPeriodHours($period) {
        switch ($period) {
            case '24h':
                return 24;
            case '7d':
                return 168;
            case '30d':
                return 720;
            default:
                return 24;
        }
    }

    public function getSystemHealth() {
        return [
            'php_version' => PHP_VERSION,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'unknown',
            'max_execution_time' => ini_get('max_execution_time'),
            'memory_limit' => ini_get('memory_limit'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'session_gc_maxlifetime' => ini_get('session.gc_maxlifetime'),
            'error_reporting' => ini_get('error_reporting'),
            'display_errors' => ini_get('display_errors'),
            'log_errors' => ini_get('log_errors'),
            'error_log' => ini_get('error_log')
        ];
    }

    public function getSecurityConfig() {
        return [
            'session_settings' => [
                'cookie_httponly' => ini_get('session.cookie_httponly'),
                'cookie_secure' => ini_get('session.cookie_secure'),
                'use_strict_mode' => ini_get('session.use_strict_mode'),
                'use_only_cookies' => ini_get('session.use_only_cookies')
            ],
            'security_headers' => [
                'x_frame_options' => $this->getHeader('X-Frame-Options'),
                'x_content_type_options' => $this->getHeader('X-Content-Type-Options'),
                'x_xss_protection' => $this->getHeader('X-XSS-Protection'),
                'content_security_policy' => $this->getHeader('Content-Security-Policy'),
                'strict_transport_security' => $this->getHeader('Strict-Transport-Security')
            ],
            'file_permissions' => $this->getFilePermissions()
        ];
    }

    private function getHeader($name) {
        $headers = getallheaders();
        return $headers[$name] ?? null;
    }

    private function getFilePermissions() {
        $files = [
            '.env',
            'config.php',
            'security_check.php',
            'csrf_protection.php',
            'security_functions.php',
            'Database.php',
            'brute_force_protection.php',
            'file_upload_security.php',
            'php_security_config.php',
            '.htaccess'
        ];

        $permissions = [];
        foreach ($files as $file) {
            if (file_exists($file)) {
                $permissions[$file] = [
                    'permissions' => substr(sprintf('%o', fileperms($file)), -4),
                    'owner' => posix_getpwuid(fileowner($file))['name'] ?? 'unknown',
                    'group' => posix_getgrgid(filegroup($file))['name'] ?? 'unknown'
                ];
            }
        }

        return $permissions;
    }
}
?> 