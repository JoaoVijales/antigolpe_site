<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Segurança - AntiGolpe</title>
    <link rel="stylesheet" href="/assets/css/dashboard.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .metric-card {
            background: linear-gradient(45deg, #2196F3, #1976D2);
            color: white;
        }
        .threat-card {
            background: linear-gradient(45deg, #F44336, #D32F2F);
            color: white;
        }
        .activity-card {
            background: linear-gradient(45deg, #4CAF50, #388E3C);
            color: white;
        }
        .system-card {
            background: linear-gradient(45deg, #FF9800, #F57C00);
            color: white;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <header class="dashboard-header">
            <h1>Dashboard de Segurança</h1>
            <nav class="dashboard-nav">
                <a href="/" class="nav-link">Voltar para Home</a>
            </nav>
        </header>

        <main class="dashboard-content">
            <!-- Seção de Estatísticas de Acesso -->
            <section class="stats-section">
                <h2>Estatísticas de Acesso</h2>
                
                <div class="stats-summary">
                    <div class="stat-card">
                        <h3>Total de Visualizações</h3>
                        <p class="stat-value"><?php echo number_format($totalStats['total_views']); ?></p>
                    </div>
                    <div class="stat-card">
                        <h3>Visitantes Únicos</h3>
                        <p class="stat-value"><?php echo number_format($totalStats['unique_visitors']); ?></p>
                    </div>
                    <div class="stat-card">
                        <h3>Páginas Visitadas</h3>
                        <p class="stat-value"><?php echo number_format($totalStats['total_pages']); ?></p>
                    </div>
                </div>

                <div class="page-stats-table">
                    <h3>Detalhes por Página</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Página</th>
                                <th>Total de Visualizações</th>
                                <th>Visitantes Únicos</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pageStatsData as $stat): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($stat['page_path']); ?></td>
                                <td><?php echo number_format($stat['total_views']); ?></td>
                                <td><?php echo number_format($stat['unique_visitors']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Seção de Métricas de Segurança -->
            <section class="security-metrics">
                <h2>Métricas de Segurança</h2>
                <div class="metrics-grid">
                    <?php foreach ($metrics as $metric): ?>
                    <div class="metric-card">
                        <h3><?php echo htmlspecialchars($metric['name']); ?></h3>
                        <p class="metric-value"><?php echo htmlspecialchars($metric['value']); ?></p>
                        <p class="metric-description"><?php echo htmlspecialchars($metric['description']); ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 