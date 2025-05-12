# Guia de Integração

Este guia fornece instruções para integrar o sistema de segurança em outras aplicações.

## Instalação

### Via Composer
```bash
composer require seu-vendor/security-system
```

### Configuração Inicial
```php
require 'vendor/autoload.php';

use SecuritySystem\SecurityManager;

$security = new SecurityManager([
    'redis' => [
        'host' => 'localhost',
        'port' => 6379,
        'password' => null
    ],
    'database' => [
        'host' => 'localhost',
        'name' => 'security_db',
        'user' => 'user',
        'pass' => 'pass'
    ]
]);
```

## Integração com Frameworks

### Laravel
```php
// config/security.php
return [
    'enabled' => true,
    'redis' => [
        'host' => env('REDIS_HOST', 'localhost'),
        'port' => env('REDIS_PORT', 6379),
    ],
    'database' => [
        'connection' => 'security',
    ]
];

// app/Providers/SecurityServiceProvider.php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use SecuritySystem\SecurityManager;

class SecurityServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(SecurityManager::class, function ($app) {
            return new SecurityManager(config('security'));
        });
    }
}
```

### Symfony
```php
# config/packages/security_system.yaml
security_system:
    enabled: true
    redis:
        host: '%env(REDIS_HOST)%'
        port: '%env(REDIS_PORT)%'
    database:
        connection: security

# src/DependencyInjection/SecuritySystemExtension.php
namespace App\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use SecuritySystem\SecurityManager;

class SecuritySystemExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setDefinition(SecurityManager::class, function () use ($config) {
            return new SecurityManager($config);
        });
    }
}
```

## Middleware

### Proteção CSRF
```php
use SecuritySystem\Middleware\CsrfProtection;

$app->add(new CsrfProtection());
```

### Proteção contra Força Bruta
```php
use SecuritySystem\Middleware\BruteForceProtection;

$app->add(new BruteForceProtection([
    'max_attempts' => 5,
    'lockout_time' => 900
]));
```

### Validação de Upload
```php
use SecuritySystem\Middleware\FileUploadProtection;

$app->add(new FileUploadProtection([
    'max_size' => 5242880,
    'allowed_types' => ['image/jpeg', 'image/png']
]));
```

## Eventos

### Registro de Eventos
```php
use SecuritySystem\Events\SecurityEvent;

$security->dispatch(new SecurityEvent('login_attempt', [
    'user_id' => 123,
    'ip' => '192.168.1.1'
]));
```

### Ouvintes de Eventos
```php
use SecuritySystem\Events\SecurityEvent;
use SecuritySystem\Listeners\AlertListener;

$security->addListener(SecurityEvent::class, new AlertListener());
```

## Logging

### Configuração
```php
use SecuritySystem\Logging\SecurityLogger;

$logger = new SecurityLogger([
    'path' => '/var/log/security',
    'level' => 'info',
    'format' => 'json'
]);
```

### Uso
```php
$logger->info('Login bem-sucedido', [
    'user_id' => 123,
    'ip' => '192.168.1.1'
]);

$logger->error('Tentativa de força bruta', [
    'ip' => '192.168.1.1',
    'attempts' => 5
]);
```

## Notificações

### Configuração
```php
use SecuritySystem\Notifications\NotificationManager;

$notifications = new NotificationManager([
    'channels' => [
        'email' => [
            'enabled' => true,
            'from' => 'security@exemplo.com'
        ],
        'slack' => [
            'enabled' => true,
            'webhook' => 'https://hooks.slack.com/...'
        ]
    ]
]);
```

### Uso
```php
$notifications->send('alert', [
    'message' => 'Tentativa de força bruta detectada',
    'severity' => 'high',
    'channels' => ['email', 'slack']
]);
```

## Cache

### Configuração
```php
use SecuritySystem\Cache\SecurityCache;

$cache = new SecurityCache([
    'driver' => 'redis',
    'prefix' => 'security:',
    'ttl' => 3600
]);
```

### Uso
```php
$cache->remember('blocked_ips', 3600, function () {
    return $security->getBlockedIps();
});
```

## Exemplos de Uso

### Proteção de Login
```php
use SecuritySystem\Security\LoginProtection;

$protection = new LoginProtection($security);

try {
    $protection->attempt($username, $password, $ip);
    // Login bem-sucedido
} catch (SecurityException $e) {
    // Tratar erro de segurança
}
```

### Upload de Arquivos
```php
use SecuritySystem\Security\FileUpload;

$upload = new FileUpload($security);

try {
    $file = $upload->validate($_FILES['file']);
    $path = $upload->save($file);
    // Arquivo salvo com sucesso
} catch (SecurityException $e) {
    // Tratar erro de segurança
}
```

### Monitoramento
```php
use SecuritySystem\Monitoring\SecurityMonitor;

$monitor = new SecurityMonitor($security);

// Verificar status
$status = $monitor->getStatus();

// Registrar métrica
$monitor->recordMetric('login_attempts', 1);

// Obter relatório
$report = $monitor->generateReport();
```

## Boas Práticas

1. **Configuração**
   - Use variáveis de ambiente para configurações sensíveis
   - Mantenha as configurações em arquivos separados
   - Documente todas as configurações

2. **Segurança**
   - Sempre valide entradas
   - Use HTTPS em todas as conexões
   - Implemente rate limiting
   - Mantenha logs de segurança

3. **Performance**
   - Use cache para dados frequentes
   - Implemente paginação em listas grandes
   - Otimize consultas ao banco de dados
   - Monitore o uso de recursos

4. **Manutenção**
   - Mantenha o sistema atualizado
   - Faça backup regularmente
   - Monitore os logs
   - Revise as configurações periodicamente

## Troubleshooting

### Problemas Comuns

#### Redis Connection Failed
```php
try {
    $redis = new Redis();
    $redis->connect('localhost', 6379);
} catch (Exception $e) {
    error_log("Redis connection failed: " . $e->getMessage());
    // Fallback para outro método de armazenamento
}
```

#### Database Connection Issues
```php
try {
    $db = new PDO("mysql:host=localhost;dbname=security", "user", "pass");
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    // Implementar retry ou fallback
}
```

#### File Permission Issues
```php
if (!is_writable('/var/log/security')) {
    error_log("Log directory is not writable");
    // Tentar criar diretório ou usar alternativa
}
```

## Suporte

Para suporte técnico:
- Email: support@exemplo.com
- Documentação: https://docs.exemplo.com
- Issues: https://github.com/seu-vendor/security-system/issues 