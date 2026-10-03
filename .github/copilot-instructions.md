# Instrucoes do GitHub Copilot

Siga as convencoes de [`AGENTS.md`](../AGENTS.md) e consulte [`README.md`](../README.md) para os comandos de instalacao e execucao.

Este repositorio e um projeto de teste para candidatura a Apresenta.me, empresa que oferece um sistema de gestao imobiliaria. Nao o descreva como produto oficial.

- Stack: Laravel 13, PHP 8.3+, Inertia.js 2, Vue 3, Tailwind CSS e Vite.
- Mantenha a interface em portugues e valide dados no backend com Form Requests.
- Reutilize os tokens `app-theme-*` e preserve o tema baseado em `prefers-color-scheme`.
- Nunca inclua `.env`, credenciais, chaves ou dados pessoais reais em alteracoes.
- Valide frontend com `npm run build` e backend com `php artisan test`.
- Se faltar uma extensao PHP, informe o bloqueio sem tentar instalacao privilegiada.
- Nao instale Laravel Boost nem execute configuradores de agente sem solicitacao.