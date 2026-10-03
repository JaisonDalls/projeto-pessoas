# Instrucoes para agentes

Leia `README.md` para instalar e executar o projeto. Agentes ajudam a trabalhar no codigo, mas nao substituem o servidor da aplicacao.

## Contexto

- Projeto de teste criado para um processo de candidatura a Apresenta.me, empresa de sistema de gestao imobiliaria. Nao o descreva como produto oficial.
- Stack: Laravel 13, PHP 8.3+, Inertia.js 2, Vue 3, Tailwind CSS e Vite.
- O CRUD principal e de `Pessoa`; as rotas usam o prefixo nomeado `pessoas.*`.
- A interface e em portugues. Os valores persistidos para tipo sao `física` e `jurídica`.

## Convencoes

- Mantenha operacoes HTTP em controllers e regras de validacao em Form Requests.
- Mantenha paginas Inertia em `resources/js/Pages` e componentes reutilizaveis em `resources/js/Components`.
- Preserve o tema que acompanha `prefers-color-scheme` e reutilize tokens `app-theme-*` de `resources/css/app.css`.
- Nunca inclua `.env`, credenciais, chaves ou dados pessoais reais em alteracoes.
- Evite alteracoes destrutivas no banco. `php artisan migrate:fresh` apaga todos os dados.

## Validacao

- Frontend: `npm run build`.
- Backend: `php artisan test` ou um teste direcionado.
- Se testes ou comandos Laravel falharem, confira extensoes PHP, especialmente `mbstring` e `pdo_sqlite`.
- Nao instale Laravel Boost nem execute configuradores de agente sem solicitacao.
