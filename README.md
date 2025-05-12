# Sistema de Segurança

Este é um sistema de segurança robusto que implementa múltiplas camadas de proteção e monitoramento.

## Documentação

A documentação completa do sistema está organizada na pasta `docs/`:

- [Guia do Desenvolvedor](docs/DEVELOPER.md)
- [Guia do Usuário](docs/USER_GUIDE.md)
- [Documentação da API](docs/API.md)
- [Arquitetura do Sistema](docs/ARCHITECTURE.md)
- [Guia de Testes](docs/TESTING.md)
- [Guia de Contribuição](docs/CONTRIBUTING.md)
- [Guia de Segurança](docs/SECURITY.md)
- [Guia de Monitoramento](docs/MONITORING.md)

## Instalação

```bash
composer install
```

## Configuração

```bash
cp .env.example .env
php artisan key:generate
```

## Uso

```bash
php artisan serve
```

## Licença

MIT 