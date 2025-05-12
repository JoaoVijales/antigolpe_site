# Guia de Contribuição

Este documento fornece diretrizes e instruções para contribuir com o sistema de segurança.

## Como Contribuir

### 1. Preparação do Ambiente

```bash
# Clone o repositório
git clone https://github.com/seu-vendor/security-system.git

# Instale as dependências
composer install

# Configure o ambiente
cp .env.example .env
php artisan key:generate
```

### 2. Fluxo de Trabalho

1. **Crie uma Branch**
   ```bash
   git checkout -b feature/nova-funcionalidade
   ```

2. **Desenvolva**
   - Siga os padrões de código
   - Escreva testes
   - Documente alterações

3. **Teste**
   ```bash
   # Execute os testes
   phpunit
   
   # Verifique a cobertura
   phpunit --coverage-html coverage/
   ```

4. **Envie as Alterações**
   ```bash
   git add .
   git commit -m "feat: adiciona nova funcionalidade"
   git push origin feature/nova-funcionalidade
   ```

## Padrões de Código

### 1. PHP

```php
// Use PSR-12
namespace SecuritySystem\Services;

use SecuritySystem\Interfaces\SecurityInterface;

class SecurityService implements SecurityInterface
{
    private const MAX_ATTEMPTS = 5;
    
    public function __construct(
        private readonly SecurityRepository $repository,
        private readonly CacheInterface $cache
    ) {
    }
    
    public function detectThreat(string $ip): bool
    {
        return $this->repository->countAttempts($ip) >= self::MAX_ATTEMPTS;
    }
}
```

### 2. JavaScript

```javascript
// Use ESLint e Prettier
const SecurityManager = {
    maxAttempts: 5,
    
    async detectThreat(ip) {
        const attempts = await this.repository.countAttempts(ip);
        return attempts >= this.maxAttempts;
    }
};
```

## Documentação

### 1. Comentários

```php
/**
 * Detecta tentativas de força bruta.
 *
 * @param string $ip Endereço IP do cliente
 * @param int $threshold Limite de tentativas
 * @return bool
 * @throws SecurityException
 */
public function detectBruteForce(string $ip, int $threshold = 5): bool
{
    // Implementação
}
```

### 2. README

- Atualize o README.md quando necessário
- Documente novas funcionalidades
- Atualize exemplos de uso

## Testes

### 1. Unitários

```php
namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use SecuritySystem\Services\SecurityService;

class SecurityServiceTest extends TestCase
{
    public function testDetectBruteForce()
    {
        $service = new SecurityService();
        $result = $service->detectBruteForce('192.168.1.1');
        $this->assertTrue($result);
    }
}
```

### 2. Integração

```php
namespace Tests\Integration;

use Tests\TestCase;

class SecurityIntegrationTest extends TestCase
{
    public function testCompleteSecurityFlow()
    {
        $response = $this->postJson('/api/security/check', [
            'ip' => '192.168.1.1'
        ]);
        
        $response->assertStatus(200);
    }
}
```

## Pull Requests

### 1. Template

```markdown
## Descrição
[Descreva as alterações]

## Tipo de Alteração
- [ ] Bug fix
- [ ] Nova funcionalidade
- [ ] Breaking change
- [ ] Documentação

## Checklist
- [ ] Testes adicionados/atualizados
- [ ] Documentação atualizada
- [ ] Código segue padrões
- [ ] Build passa
```

### 2. Processo de Revisão

1. **Code Review**
   - Verifique alterações
   - Sugira melhorias
   - Aprove ou rejeite

2. **CI/CD**
   - Verifique build
   - Verifique testes
   - Verifique cobertura

## Comunicação

### 1. Issues

- Use templates
- Forneça contexto
- Inclua logs
- Descreva ambiente

### 2. Discussões

- Use o fórum do projeto
- Mantenha foco
- Seja respeitoso
- Ajude outros

## Segurança

### 1. Vulnerabilidades

- Reporte em privado
- Não divulgue publicamente
- Forneça detalhes
- Ajude na correção

### 2. Boas Práticas

- Valide entradas
- Sanitize saídas
- Use HTTPS
- Mantenha dependências atualizadas

## Manutenção

### 1. Código Legado

- Documente
- Adicione testes
- Refatore gradualmente
- Mantenha compatibilidade

### 2. Dependências

- Mantenha atualizadas
- Verifique vulnerabilidades
- Documente mudanças
- Teste atualizações

## Licença

### 1. MIT License

```
MIT License

Copyright (c) 2024 Seu Nome

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```

## Conclusão

Contribuir para o projeto significa:
- Seguir padrões
- Manter qualidade
- Documentar mudanças
- Respeitar comunidade
- Aprender e ensinar 