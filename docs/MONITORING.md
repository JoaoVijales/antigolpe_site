# Guia de Monitoramento

Este documento descreve as práticas e ferramentas de monitoramento do sistema de segurança.

## Visão Geral

O sistema implementa monitoramento em múltiplas camadas:
- Métricas do Sistema
- Logs de Segurança
- Alertas
- Dashboards
- Relatórios

## Métricas do Sistema

### 1. Métricas de Hardware

```php
class SystemMetrics
{
    public function getCpuUsage(): float
    {
        $load = sys_getloadavg();
        return $load[0];
    }

    public function getMemoryUsage(): array
    {
        $free = shell_exec('free');
        $free = (string)trim($free);
        $free_arr = explode("\n", $free);
        $mem = explode(" ", $free_arr[1]);
        $mem = array_filter($mem);
        $mem = array_merge($mem);
        
        return [
            'total' => $mem[1],
            'used' => $mem[2],
            'free' => $mem[3]
        ];
    }

    public function getDiskUsage(): array
    {
        $total = disk_total_space('/');
        $free = disk_free_space('/');
        $used = $total - $free;
        
        return [
            'total' => $total,
            'used' => $used,
            'free' => $free
        ];
    }
}
```

### 2. Métricas de Aplicação

```php
class ApplicationMetrics
{
    public function getRequestMetrics(): array
    {
        return [
            'total_requests' => $this->getTotalRequests(),
            'active_users' => $this->getActiveUsers(),
            'response_time' => $this->getAverageResponseTime(),
            'error_rate' => $this->getErrorRate()
        ];
    }

    public function getSecurityMetrics(): array
    {
        return [
            'login_attempts' => $this->getLoginAttempts(),
            'blocked_ips' => $this->getBlockedIps(),
            'active_threats' => $this->getActiveThreats(),
            'security_score' => $this->getSecurityScore()
        ];
    }
}
```

## Logs de Segurança

### 1. Estrutura de Logs

```php
class SecurityLog
{
    public function logEvent(string $type, array $data): void
    {
        $log = [
            'timestamp' => time(),
            'type' => $type,
            'data' => $data,
            'ip' => $this->getClientIp(),
            'user_id' => $this->getCurrentUserId(),
            'severity' => $this->determineSeverity($type)
        ];

        $this->repository->create($log);
    }

    private function determineSeverity(string $type): string
    {
        $severityMap = [
            'login_attempt' => 'info',
            'brute_force' => 'warning',
            'xss_attempt' => 'critical',
            'sql_injection' => 'critical'
        ];

        return $severityMap[$type] ?? 'info';
    }
}
```

### 2. Rotação de Logs

```php
class LogRotation
{
    public function rotate(): void
    {
        $logs = $this->repository->getOldLogs();
        
        foreach ($logs as $log) {
            // 1. Arquivar
            $this->archive($log);
            
            // 2. Remover
            $this->repository->delete($log->id);
        }
    }

    private function archive(Log $log): void
    {
        $archivePath = storage_path('logs/archive/' . date('Y/m'));
        
        if (!file_exists($archivePath)) {
            mkdir($archivePath, 0755, true);
        }

        $filename = $archivePath . '/security-' . date('Y-m-d') . '.log';
        file_put_contents($filename, json_encode($log) . "\n", FILE_APPEND);
    }
}
```

## Alertas

### 1. Configuração de Alertas

```php
class AlertConfiguration
{
    private $thresholds = [
        'cpu_usage' => 80,
        'memory_usage' => 85,
        'disk_usage' => 90,
        'error_rate' => 5,
        'response_time' => 1000
    ];

    public function checkThresholds(): array
    {
        $alerts = [];
        
        foreach ($this->thresholds as $metric => $threshold) {
            $value = $this->getMetricValue($metric);
            if ($value > $threshold) {
                $alerts[] = [
                    'metric' => $metric,
                    'value' => $value,
                    'threshold' => $threshold
                ];
            }
        }
        
        return $alerts;
    }
}
```

### 2. Notificações

```php
class AlertNotification
{
    public function sendAlert(array $alert): void
    {
        // 1. Determinar canais
        $channels = $this->determineChannels($alert);
        
        // 2. Enviar notificações
        foreach ($channels as $channel) {
            $this->sendToChannel($channel, $alert);
        }
    }

    private function determineChannels(array $alert): array
    {
        $channels = ['log'];
        
        if ($alert['severity'] === 'critical') {
            $channels = array_merge($channels, ['email', 'slack', 'sms']);
        }
        
        return $channels;
    }
}
```

## Dashboards

### 1. Métricas em Tempo Real

```php
class RealTimeDashboard
{
    public function getMetrics(): array
    {
        return [
            'system' => [
                'cpu' => $this->getCpuMetrics(),
                'memory' => $this->getMemoryMetrics(),
                'disk' => $this->getDiskMetrics()
            ],
            'security' => [
                'threats' => $this->getThreatMetrics(),
                'alerts' => $this->getAlertMetrics(),
                'logs' => $this->getLogMetrics()
            ],
            'performance' => [
                'response_time' => $this->getResponseTimeMetrics(),
                'error_rate' => $this->getErrorRateMetrics(),
                'throughput' => $this->getThroughputMetrics()
            ]
        ];
    }
}
```

### 2. Gráficos e Visualizações

```php
class DashboardVisualization
{
    public function getCharts(): array
    {
        return [
            'threat_timeline' => $this->getThreatTimeline(),
            'resource_usage' => $this->getResourceUsage(),
            'alert_distribution' => $this->getAlertDistribution(),
            'performance_trends' => $this->getPerformanceTrends()
        ];
    }
}
```

## Relatórios

### 1. Relatórios Diários

```php
class DailyReport
{
    public function generate(): array
    {
        return [
            'summary' => [
                'total_requests' => $this->getTotalRequests(),
                'total_threats' => $this->getTotalThreats(),
                'total_alerts' => $this->getTotalAlerts()
            ],
            'threats' => [
                'by_type' => $this->getThreatsByType(),
                'by_severity' => $this->getThreatsBySeverity(),
                'by_source' => $this->getThreatsBySource()
            ],
            'performance' => [
                'average_response_time' => $this->getAverageResponseTime(),
                'error_rate' => $this->getErrorRate(),
                'resource_usage' => $this->getResourceUsage()
            ]
        ];
    }
}
```

### 2. Relatórios Personalizados

```php
class CustomReport
{
    public function generate(array $parameters): array
    {
        return [
            'time_range' => [
                'start' => $parameters['start_date'],
                'end' => $parameters['end_date']
            ],
            'metrics' => $this->getSelectedMetrics($parameters['metrics']),
            'filters' => $this->applyFilters($parameters['filters']),
            'grouping' => $this->applyGrouping($parameters['grouping'])
        ];
    }
}
```

## Integração com Ferramentas

### 1. Prometheus

```php
class PrometheusIntegration
{
    public function exportMetrics(): void
    {
        $metrics = [
            'security_threats_total' => $this->getTotalThreats(),
            'security_alerts_total' => $this->getTotalAlerts(),
            'security_blocked_ips' => $this->getBlockedIps(),
            'system_cpu_usage' => $this->getCpuUsage(),
            'system_memory_usage' => $this->getMemoryUsage()
        ];

        foreach ($metrics as $name => $value) {
            $this->prometheus->gauge($name, $value);
        }
    }
}
```

### 2. Grafana

```php
class GrafanaIntegration
{
    public function getDashboardData(): array
    {
        return [
            'panels' => [
                'security' => $this->getSecurityPanels(),
                'system' => $this->getSystemPanels(),
                'performance' => $this->getPerformancePanels()
            ],
            'variables' => $this->getDashboardVariables(),
            'annotations' => $this->getDashboardAnnotations()
        ];
    }
}
```

## Boas Práticas

### 1. Configuração

- Defina thresholds apropriados
- Configure retenção de logs
- Estabeleça políticas de alerta
- Mantenha dashboards atualizados

### 2. Manutenção

- Revise métricas regularmente
- Ajuste thresholds conforme necessário
- Limpe logs antigos
- Atualize visualizações

## Troubleshooting

### 1. Problemas Comuns

#### Alta Utilização de CPU
```bash
# Verificar processos
top -c

# Analisar logs
tail -f /var/log/security.log
```

#### Falhas de Disco
```bash
# Verificar espaço
df -h

# Limpar logs antigos
find /var/log -type f -mtime +30 -delete
```

#### Alertas Falsos
```php
// Ajustar thresholds
$thresholds = [
    'cpu_usage' => 85,  // Aumentado de 80
    'memory_usage' => 90 // Aumentado de 85
];
```

## Conclusão

O monitoramento efetivo requer:
- Métricas relevantes
- Alertas apropriados
- Dashboards informativos
- Manutenção regular
- Ajustes contínuos 