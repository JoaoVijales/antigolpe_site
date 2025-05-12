<?php
require_once 'Logger.php';
require_once 'Database.php';
require_once 'SecurityDashboard.php';

session_start();

// Verificar autenticação
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit;
}

$logger = new Logger('logs/security.log');
$db = new Database();
$dashboard = new SecurityDashboard($logger, $db);

// Obter dados do dashboard
$metrics = $dashboard->getSecurityMetrics();
$stats = $dashboard->getSecurityStats($_GET['period'] ?? '24h');
$systemHealth = $dashboard->getSystemHealth();
$securityConfig = $dashboard->getSecurityConfig();
$logs = $dashboard->getSecurityLogs();

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Segurança</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/font-awesome@6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .card {
            margin-bottom: 1rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .metric-card {
            text-align: center;
            padding: 1rem;
        }
        .metric-value {
            font-size: 2rem;
            font-weight: bold;
        }
        .metric-label {
            color: #6c757d;
        }
        .chart-container {
            position: relative;
            height: 300px;
            margin-bottom: 1rem;
        }
        .table-responsive {
            max-height: 400px;
            overflow-y: auto;
        }
        .status-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }
        .status-ok {
            background-color: #28a745;
        }
        .status-warning {
            background-color: #ffc107;
        }
        .status-danger {
            background-color: #dc3545;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Dashboard de Segurança</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="#metrics">Métricas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#stats">Estatísticas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#system">Sistema</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#logs">Logs</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <!-- Métricas -->
        <section id="metrics" class="mb-4">
            <h2>Métricas de Segurança</h2>
            <div class="row">
                <div class="col-md-3">
                    <div class="card metric-card">
                        <div class="metric-value"><?php echo $metrics['total_alerts']; ?></div>
                        <div class="metric-label">Total de Alertas</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card metric-card">
                        <div class="metric-value"><?php echo count($metrics['active_threats']); ?></div>
                        <div class="metric-label">Ameaças Ativas</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card metric-card">
                        <div class="metric-value"><?php echo count($metrics['blocked_ips']); ?></div>
                        <div class="metric-label">IPs Bloqueados</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card metric-card">
                        <div class="metric-value"><?php echo count($metrics['failed_logins']); ?></div>
                        <div class="metric-label">Falhas de Login</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Estatísticas -->
        <section id="stats" class="mb-4">
            <h2>Estatísticas</h2>
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Alertas por Tipo</h5>
                            <div class="chart-container">
                                <canvas id="alertsByTypeChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Alertas por Severidade</h5>
                            <div class="chart-container">
                                <canvas id="alertsBySeverityChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Timeline de Alertas</h5>
                            <div class="chart-container">
                                <canvas id="alertsTimelineChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Saúde do Sistema -->
        <section id="system" class="mb-4">
            <h2>Saúde do Sistema</h2>
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Configurações do PHP</h5>
                            <table class="table">
                                <tbody>
                                    <?php foreach ($systemHealth as $key => $value): ?>
                                    <tr>
                                        <td><?php echo ucwords(str_replace('_', ' ', $key)); ?></td>
                                        <td><?php echo $value; ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Configurações de Segurança</h5>
                            <table class="table">
                                <tbody>
                                    <?php foreach ($securityConfig['security_headers'] as $key => $value): ?>
                                    <tr>
                                        <td><?php echo ucwords(str_replace('_', ' ', $key)); ?></td>
                                        <td>
                                            <span class="status-indicator <?php echo $value ? 'status-ok' : 'status-danger'; ?>"></span>
                                            <?php echo $value ?: 'Não configurado'; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Logs -->
        <section id="logs" class="mb-4">
            <h2>Logs de Segurança</h2>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Data/Hora</th>
                                    <th>Tipo</th>
                                    <th>Severidade</th>
                                    <th>Mensagem</th>
                                    <th>IP</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($logs as $log): ?>
                                <tr>
                                    <td><?php echo $log['created_at']; ?></td>
                                    <td><?php echo $log['type']; ?></td>
                                    <td>
                                        <span class="badge bg-<?php 
                                            echo $log['severity'] === 'critical' ? 'danger' : 
                                                ($log['severity'] === 'error' ? 'warning' : 'info'); 
                                        ?>">
                                            <?php echo $log['severity']; ?>
                                        </span>
                                    </td>
                                    <td><?php echo $log['message']; ?></td>
                                    <td><?php echo $log['ip']; ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Gráfico de Alertas por Tipo
        new Chart(document.getElementById('alertsByTypeChart'), {
            type: 'pie',
            data: {
                labels: <?php echo json_encode(array_column($stats['alerts_by_type'], 'type')); ?>,
                datasets: [{
                    data: <?php echo json_encode(array_column($stats['alerts_by_type'], 'count')); ?>,
                    backgroundColor: [
                        '#dc3545',
                        '#ffc107',
                        '#28a745',
                        '#17a2b8',
                        '#6c757d'
                    ]
                }]
            }
        });

        // Gráfico de Alertas por Severidade
        new Chart(document.getElementById('alertsBySeverityChart'), {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode(array_column($stats['alerts_by_severity'], 'severity')); ?>,
                datasets: [{
                    data: <?php echo json_encode(array_column($stats['alerts_by_severity'], 'count')); ?>,
                    backgroundColor: [
                        '#dc3545',
                        '#ffc107',
                        '#28a745'
                    ]
                }]
            }
        });

        // Gráfico de Timeline de Alertas
        new Chart(document.getElementById('alertsTimelineChart'), {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_column($stats['alerts_timeline'], 'hour')); ?>,
                datasets: [{
                    label: 'Alertas',
                    data: <?php echo json_encode(array_column($stats['alerts_timeline'], 'count')); ?>,
                    borderColor: '#dc3545',
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html> 