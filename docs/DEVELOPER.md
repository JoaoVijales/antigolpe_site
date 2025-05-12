# Documentação para Desenvolvedores

Este documento contém informações técnicas detalhadas para desenvolvedores que trabalham com o sistema de segurança.

## Estrutura do Projeto

```
.
├── app/                    # Código principal da aplicação
│   ├── Controllers/       # Controladores
│   ├── Models/           # Modelos
│   ├── Services/         # Serviços
│   └── Helpers/          # Funções auxiliares
├── public/               # Arquivos públicos
│   ├── css/             # Estilos
│   ├── js/              # Scripts
│   └── index.php        # Ponto de entrada
├── resources/           # Recursos
│   ├── views/          # Templates
│   └── lang/           # Traduções
├── storage/            # Armazenamento
│   ├── logs/          # Logs
│   └── cache/         # Cache
└── tests/             # Testes
    ├── Unit/         # Testes unitários
    └── Integration/  # Testes de integração
```

## Arquitetura

O sistema segue o padrão MVC (Model-View-Controller) com algumas adaptações:

### Controllers
- `SecurityController`: Gerencia operações de segurança
- `LogController`: Gerencia logs e relatórios
- `AlertController`: Gerencia notificações e alertas
- `DashboardController`: Gerencia o dashboard

### Models
- `SecurityLog`: Modelo para logs de segurança
- `Alert`: Modelo para alertas
- `User`: Modelo para usuários
- `Setting`: Modelo para configurações

### Services
- `SecurityService`: Serviço principal de segurança
- `LogService`: Serviço de logging
- `AlertService`: Serviço de notificações
- `FileService`: Serviço de manipulação de arquivos

## Componentes de Segurança

### Proteção contra Força Bruta
```php
$bruteForce = new BruteForceProtection();
$bruteForce->checkAttempts($identifier);
$bruteForce->logAttempt($identifier, $success);
```

### Proteção CSRF
```php
$token = generateCSRFToken();
validateCSRFToken($token);
regenerateCSRFToken();
```

### Proteção de Upload
```php
$uploadSecurity = new FileUploadSecurity();
if ($uploadSecurity->validateFile($file)) {
    $filename = $uploadSecurity->saveFile($file);
}
```

## Banco de Dados

### Tabelas Principais

#### security_logs
```sql
CREATE TABLE security_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    event_type VARCHAR(50) NOT NULL,
    severity ENUM('low', 'medium', 'high', 'critical') NOT NULL,
    message TEXT NOT NULL,
    ip_address VARCHAR(45),
    user_id BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_event_type (event_type),
    INDEX idx_severity (severity),
    INDEX idx_created_at (created_at)
);
```

#### alerts
```sql
CREATE TABLE alerts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(50) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('pending', 'sent', 'failed') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    sent_at TIMESTAMP NULL,
    INDEX idx_type (type),
    INDEX idx_status (status)
);
```

## APIs

### Endpoints de Segurança

#### GET /api/security/status
Retorna o status atual do sistema de segurança.

```json
{
    "status": "active",
    "threats": {
        "active": 0,
        "blocked": 5
    },
    "last_check": "2024-03-20T10:00:00Z"
}
```

#### POST /api/security/log
Registra um novo evento de segurança.

```json
{
    "event_type": "login_attempt",
    "severity": "medium",
    "message": "Múltiplas tentativas de login",
    "ip_address": "192.168.1.1"
}
```

## Testes

### Testes Unitários
```bash
./vendor/bin/phpunit tests/Unit
```

### Testes de Integração
```bash
./vendor/bin/phpunit tests/Integration
```

### Cobertura de Código
```bash
./vendor/bin/phpunit --coverage-html coverage
```

## Logging

### Níveis de Log
- `DEBUG`: Informações detalhadas para debug
- `INFO`: Informações gerais
- `WARNING`: Avisos
- `ERROR`: Erros
- `CRITICAL`: Erros críticos

### Formato do Log
```json
{
    "timestamp": "2024-03-20T10:00:00Z",
    "level": "ERROR",
    "message": "Falha na autenticação",
    "context": {
        "user_id": 123,
        "ip": "192.168.1.1"
    }
}
```

## Cache

O sistema utiliza Redis para cache com as seguintes chaves:

- `security:attempts:{ip}`: Tentativas de login
- `security:blocked:{ip}`: IPs bloqueados
- `security:session:{id}`: Sessões ativas
- `security:token:{id}`: Tokens CSRF

## Eventos

O sistema emite os seguintes eventos:

- `security.login.attempt`: Tentativa de login
- `security.login.success`: Login bem-sucedido
- `security.login.failure`: Falha no login
- `security.file.upload`: Upload de arquivo
- `security.alert.triggered`: Alerta disparado

## Contribuição

### Padrões de Código
- PSR-12 para estilo de código
- PHPDoc para documentação
- Testes unitários obrigatórios
- Cobertura mínima de 80%

### Processo de Pull Request
1. Crie uma branch feature
2. Adicione testes
3. Atualize documentação
4. Execute testes
5. Envie PR

## Troubleshooting

### Problemas Comuns

#### Redis Connection Failed
```php
try {
    $redis = new Redis();
    $redis->connect('localhost', 6379);
} catch (Exception $e) {
    error_log("Redis connection failed: " . $e->getMessage());
}
```

#### Log Directory Not Writable
```bash
chmod -R 755 storage/logs
chown -R www-data:www-data storage/logs
```

#### Session Issues
```php
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.use_only_cookies', 1);
```

## Performance

### Otimizações Recomendadas

1. Cache de Consultas Frequentes
```php
$cache->remember('security_status', 300, function() {
    return SecurityService::getStatus();
});
```

2. Índices no Banco de Dados
```sql
ALTER TABLE security_logs ADD INDEX idx_composite (event_type, severity, created_at);
```

3. Limpeza de Logs
```php
$logService->cleanOldLogs(30); // 30 dias
```

## Segurança

### Boas Práticas

1. Validação de Entrada
```php
$input = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
```

2. Sanitização de Saída
```php
htmlspecialchars($output, ENT_QUOTES, 'UTF-8');
```

3. Criptografia
```php
$encrypted = openssl_encrypt($data, 'AES-256-CBC', $key, 0, $iv);
```

## Monitoramento

### Métricas Importantes

- Taxa de tentativas de login
- Número de IPs bloqueados
- Tempo de resposta do sistema
- Uso de recursos
- Erros por tipo

### Alertas

- Tentativas de força bruta
- Uploads suspeitos
- Erros críticos
- Uso anormal de recursos
- Falhas de autenticação 