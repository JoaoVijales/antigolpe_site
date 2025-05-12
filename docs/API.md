# Documentação da API

Este documento descreve os endpoints da API do sistema de segurança.

## Autenticação

### POST /api/auth/login
Autentica um usuário e retorna um token JWT.

**Request:**
```json
{
    "email": "usuario@exemplo.com",
    "password": "senha123"
}
```

**Response (200):**
```json
{
    "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
    "expires_in": 3600,
    "user": {
        "id": 1,
        "name": "Usuário Exemplo",
        "email": "usuario@exemplo.com",
        "role": "admin"
    }
}
```

### POST /api/auth/refresh
Renova o token JWT.

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
    "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
    "expires_in": 3600
}
```

## Segurança

### GET /api/security/status
Retorna o status atual do sistema.

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
    "status": "active",
    "threats": {
        "active": 0,
        "blocked": 5
    },
    "last_check": "2024-03-20T10:00:00Z",
    "metrics": {
        "login_attempts": 10,
        "blocked_ips": 3,
        "active_sessions": 25
    }
}
```

### POST /api/security/log
Registra um novo evento de segurança.

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request:**
```json
{
    "event_type": "login_attempt",
    "severity": "medium",
    "message": "Múltiplas tentativas de login",
    "ip_address": "192.168.1.1",
    "user_id": 123,
    "metadata": {
        "browser": "Chrome",
        "os": "Windows"
    }
}
```

**Response (201):**
```json
{
    "id": 456,
    "created_at": "2024-03-20T10:00:00Z"
}
```

### GET /api/security/logs
Lista eventos de segurança.

**Headers:**
```
Authorization: Bearer {token}
```

**Query Parameters:**
- `page`: Número da página (default: 1)
- `per_page`: Itens por página (default: 20)
- `event_type`: Filtro por tipo de evento
- `severity`: Filtro por severidade
- `start_date`: Data inicial (YYYY-MM-DD)
- `end_date`: Data final (YYYY-MM-DD)

**Response (200):**
```json
{
    "data": [
        {
            "id": 456,
            "event_type": "login_attempt",
            "severity": "medium",
            "message": "Múltiplas tentativas de login",
            "ip_address": "192.168.1.1",
            "user_id": 123,
            "created_at": "2024-03-20T10:00:00Z"
        }
    ],
    "meta": {
        "current_page": 1,
        "per_page": 20,
        "total": 100,
        "total_pages": 5
    }
}
```

## Alertas

### GET /api/alerts
Lista alertas ativos.

**Headers:**
```
Authorization: Bearer {token}
```

**Query Parameters:**
- `page`: Número da página
- `per_page`: Itens por página
- `type`: Filtro por tipo
- `status`: Filtro por status

**Response (200):**
```json
{
    "data": [
        {
            "id": 789,
            "type": "brute_force",
            "message": "Detectadas múltiplas tentativas de login",
            "severity": "high",
            "status": "active",
            "created_at": "2024-03-20T10:00:00Z"
        }
    ],
    "meta": {
        "current_page": 1,
        "per_page": 20,
        "total": 50,
        "total_pages": 3
    }
}
```

### POST /api/alerts/{id}/resolve
Resolve um alerta.

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
    "id": 789,
    "status": "resolved",
    "resolved_at": "2024-03-20T10:05:00Z"
}
```

## Configurações

### GET /api/settings
Lista configurações do sistema.

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
    "security": {
        "max_login_attempts": 5,
        "login_block_duration": 900,
        "session_lifetime": 3600
    },
    "notifications": {
        "email_enabled": true,
        "slack_enabled": true,
        "sms_enabled": false
    },
    "logging": {
        "retention_days": 30,
        "level": "info"
    }
}
```

### PUT /api/settings
Atualiza configurações do sistema.

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request:**
```json
{
    "security": {
        "max_login_attempts": 3,
        "login_block_duration": 1800
    }
}
```

**Response (200):**
```json
{
    "message": "Configurações atualizadas com sucesso",
    "updated_at": "2024-03-20T10:00:00Z"
}
```

## Códigos de Erro

### 400 Bad Request
```json
{
    "error": "Parâmetros inválidos",
    "details": {
        "field": "O campo é obrigatório"
    }
}
```

### 401 Unauthorized
```json
{
    "error": "Não autorizado",
    "message": "Token inválido ou expirado"
}
```

### 403 Forbidden
```json
{
    "error": "Acesso negado",
    "message": "Você não tem permissão para acessar este recurso"
}
```

### 404 Not Found
```json
{
    "error": "Recurso não encontrado",
    "message": "O recurso solicitado não existe"
}
```

### 429 Too Many Requests
```json
{
    "error": "Muitas requisições",
    "message": "Limite de requisições excedido",
    "retry_after": 60
}
```

### 500 Internal Server Error
```json
{
    "error": "Erro interno do servidor",
    "message": "Ocorreu um erro inesperado"
}
```

## Rate Limiting

A API implementa rate limiting para proteger contra abusos:

- 100 requisições por minuto por IP
- 1000 requisições por hora por usuário
- 10000 requisições por dia por aplicação

Os limites são retornados nos headers:
```
X-RateLimit-Limit: 100
X-RateLimit-Remaining: 95
X-RateLimit-Reset: 1616236800
```

## Versão da API

A versão atual da API é v1. Para especificar a versão, use o header:
```
Accept: application/vnd.security.v1+json
```

## Changelog

### v1.0.0 (2024-03-20)
- Implementação inicial da API
- Endpoints de autenticação
- Endpoints de segurança
- Endpoints de alertas
- Endpoints de configurações 