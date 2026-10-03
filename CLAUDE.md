# Instrucoes para Claude Code

Antes de alterar o projeto, consulte `README.md` e `AGENTS.md`. O README documenta instalacao e execucao; AGENTS registra arquitetura, convencoes e validacoes.

- Este e um projeto de teste para candidatura a Apresenta.me, empresa que oferece um sistema de gestao imobiliaria; nao o apresente como produto oficial.
- Mantenha a interface em portugues, validacoes no backend com Form Requests e rotas de pessoas sob `pessoas.*`.
- Preserve o tema adaptativo do sistema operacional e os tokens `app-theme-*` em `resources/css/app.css`.
- Nunca exponha nem inclua valores de `.env`, credenciais ou dados pessoais reais em respostas e alteracoes.
- Rode `npm run build` para validar alteracoes frontend e `php artisan test` para alteracoes backend.
- Se extensoes PHP estiverem faltando, informe o bloqueio; nao tente instalacao privilegiada.
- Nao instale Laravel Boost nem altere configuracoes do Claude sem pedido explicito.
