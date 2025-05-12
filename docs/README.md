# Sistema de Segurança

Este é um sistema abrangente de segurança para aplicações PHP, que inclui monitoramento, logging, alertas e um dashboard para visualização de métricas de segurança.

## Funcionalidades

### Monitoramento de Segurança
- Verificação de ameaças ativas
- Monitoramento de tentativas de login
- Detecção de atividades suspeitas
- Verificação de IPs bloqueados
- Análise de logs em tempo real
- Monitoramento de recursos do sistema

### Logging e Alertas
- Logs detalhados de eventos de segurança
- Alertas via email, Slack, SMS e webhook
- Relatórios diários de segurança
- Limpeza automática de logs antigos
- Categorização de eventos por severidade
- Exportação de logs em múltiplos formatos

### Dashboard de Segurança
- Métricas em tempo real
- Gráficos de alertas por tipo e severidade
- Timeline de eventos
- Status do sistema e configurações
- Visualização de tendências
- Relatórios personalizáveis

### Proteções Implementadas
- Proteção contra XSS
- Proteção contra CSRF
- Proteção contra SQL Injection
- Proteção contra força bruta
- Proteção de upload de arquivos
- Cabeçalhos de segurança
- Gerenciamento de sessão seguro
- Validação de entrada de dados
- Sanitização de saída
- Criptografia de dados sensíveis

## Requisitos

### Sistema
- PHP 7.4 ou superior
- MySQL 5.7 ou superior
- Redis 6.0 ou superior
- Servidor web (Apache/Nginx)
- SSL/TLS para HTTPS

### Extensões PHP
- PDO
- PDO_MySQL
- Redis
- JSON
- OpenSSL
- mbstring
- fileinfo
- gd (para manipulação de imagens)
- curl (para notificações)

## Instalação

1. Clone o repositório:
```bash
git clone https://seu-repositorio/security-system.git
cd security-system
```

2. Instale as dependências:
```bash
composer install
```

3. Configure o banco de dados:
```bash
mysql -u seu_usuario -p seu_banco < security_logs.sql
```

4. Configure as variáveis de ambiente:
```bash
cp .env.example .env
# Edite o arquivo .env com suas configurações
```

5. Configure as permissões:
```bash
chmod +x setup_cron.sh
sudo ./setup_cron.sh
```

6. Configure o servidor web:
- Certifique-se de que o diretório `logs` é gravável pelo usuário do servidor web
- Configure o PHP para usar as configurações de segurança recomendadas
- Configure o servidor web para usar HTTPS

## Configuração

### Variáveis de Ambiente

Edite o arquivo `.env` com suas configurações:

```env
# Banco de Dados
DB_HOST=localhost
DB_NAME=seu_banco
DB_USER=seu_usuario
DB_PASS=sua_senha

# Redis
REDIS_HOST=localhost
REDIS_PORT=6379
REDIS_PASSWORD=

# Email
MAIL_HOST=smtp.seu-servidor.com
MAIL_PORT=587
MAIL_USER=seu_email@dominio.com
MAIL_PASS=sua_senha
MAIL_FROM=security@seu-dominio.com

# Slack
SLACK_WEBHOOK_URL=https://hooks.slack.com/services/xxx/yyy/zzz

# SMS
SMS_PROVIDER=twilio
SMS_ACCOUNT_SID=seu_sid
SMS_AUTH_TOKEN=seu_token
SMS_FROM=seu_numero

# Webhook
WEBHOOK_URL=https://seu-webhook.com/endpoint
WEBHOOK_TOKEN=seu_token
```

### Configurações de Segurança

As configurações de segurança podem ser ajustadas no banco de dados através da tabela `security_settings`. Algumas configurações importantes:

- `max_login_attempts`: Número máximo de tentativas de login antes do bloqueio
- `login_block_duration`: Duração do bloqueio em segundos
- `session_lifetime`: Tempo de vida da sessão em segundos
- `password_min_length`: Tamanho mínimo da senha
- `max_file_size`: Tamanho máximo de upload em bytes
- `allowed_file_types`: Tipos de arquivo permitidos para upload
- `log_retention_days`: Dias de retenção de logs
- `alert_threshold`: Limite para envio de alertas
- `ip_whitelist`: Lista de IPs confiáveis
- `ip_blacklist`: Lista de IPs bloqueados

## Uso

### Dashboard de Segurança

Acesse o dashboard em:
```
https://seu-dominio.com/security_dashboard.php
```

### Verificações de Segurança

As verificações de segurança são executadas automaticamente a cada hora através do cron job. Você também pode executar manualmente:

```bash
php security_cron.php
```

### Logs

Os logs são armazenados em:
- `logs/security.log`: Log principal de segurança
- `logs/cron.log`: Log das execuções do cron
- `logs/security_report_YYYY-MM-DD.txt`: Relatórios diários
- `logs/access.log`: Log de acesso
- `logs/error.log`: Log de erros
- `logs/audit.log`: Log de auditoria

## Manutenção

### Limpeza de Logs

Os logs são limpos automaticamente:
- Logs de segurança: após 30 dias
- Tentativas de login: após 7 dias
- Atividades suspeitas: após 7 dias
- Ameaças ativas: após 7 dias
- Logs de auditoria: após 90 dias

### Backup

Recomenda-se configurar backups regulares:
- Banco de dados (diário)
- Arquivos de configuração (semanal)
- Logs importantes (diário)
- Certificados SSL (mensal)

## Segurança

### Boas Práticas

1. Mantenha o PHP e todas as dependências atualizadas
2. Use HTTPS em todas as conexões
3. Configure corretamente as permissões de arquivos
4. Monitore regularmente os logs de segurança
5. Mantenha backups atualizados
6. Use senhas fortes e autenticação de dois fatores
7. Limite o acesso ao dashboard apenas a administradores
8. Implemente rate limiting em APIs
9. Use headers de segurança
10. Mantenha um registro de auditoria

### Headers de Segurança

O sistema configura automaticamente os seguintes headers:

- `X-Frame-Options`: DENY
- `X-Content-Type-Options`: nosniff
- `X-XSS-Protection`: 1; mode=block
- `Content-Security-Policy`: default-src 'self'
- `Strict-Transport-Security`: max-age=31536000; includeSubDomains
- `Referrer-Policy`: strict-origin-when-cross-origin
- `Permissions-Policy`: geolocation=(), microphone=(), camera=()

## Suporte

Para suporte, entre em contato:
- Email: security@seu-dominio.com
- Slack: #security-support
- Issues: https://github.com/seu-repositorio/security-system/issues

## Contribuição

1. Faça um fork do projeto
2. Crie uma branch para sua feature (`git checkout -b feature/nova-feature`)
3. Commit suas mudanças (`git commit -am 'Adiciona nova feature'`)
4. Push para a branch (`git push origin feature/nova-feature`)
5. Crie um Pull Request

## Licença

Este projeto está licenciado sob a licença MIT - veja o arquivo [LICENSE](LICENSE) para detalhes. 