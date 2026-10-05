# Projeto Pessoas

Aplicação web de cadastro e gerenciamento de pessoas, com operações para listar, pesquisar, filtrar, visualizar, cadastrar, editar e excluir registros.

Este é um projeto de teste desenvolvido durante um processo de candidatura à [Apresenta.me](https://apresenta.me/), empresa que oferece um sistema de gestão imobiliária. É um protótipo para demonstração técnica, não um produto oficial da empresa.

## Tecnologias

- PHP 8.3 ou superior e Laravel 13
- Inertia.js 2, Vue 3 e Ziggy
- Tailwind CSS e Vite 8
- SQLite por padrão; MySQL também pode ser configurado

A interface acompanha automaticamente a preferência de aparência do sistema operacional (claro ou escuro).

## Executar com Docker

- Git e Docker com o plugin Docker Compose

Clone o repositório e entre na pasta:

```sh
git clone https://github.com/JaisonDalls/projeto-pessoas.git
cd projeto-pessoas
```

Inicie a aplicação com um único comando:

```sh
docker compose up -d
```

Na primeira execução, o Docker compila a imagem do backend e do frontend, cria a chave da aplicação e prepara o banco SQLite. Quando o processo terminar, acesse <http://localhost:8000>.

O banco de dados e a chave da aplicação ficam no volume Docker `projeto-pessoas_app-data`, preservados ao recriar o container. Para acompanhar a inicialização e os logs:

```sh
docker compose logs -f app
```

Para parar os containers sem apagar os dados:

```sh
docker compose down
```

Para reconstruir a imagem após alterações no código:

```sh
docker compose up -d --build
```

O frontend é compilado durante a criação da imagem. Não é necessário instalar PHP, Composer ou Node.js na máquina para executar a aplicação com Docker.

### Publicar no Render

Crie um **Web Service** conectado ao repositório e selecione **Docker** como ambiente. Use `Dockerfile` na raiz como caminho do arquivo e configure `/up` como health check. A imagem escuta em `0.0.0.0` na porta definida por `PORT` (o padrão é `10000`, conforme o Render).

Configure as seguintes variáveis no serviço. `APP_URL` deve usar `https` e o domínio atribuído pelo Render:

```text
APP_ENV=production
APP_DEBUG=false
APP_KEY=<chave gerada para produção>
APP_URL=https://projeto-pessoas.onrender.com
```

Gere uma chave localmente com `php artisan key:generate --show` e informe o resultado como `APP_KEY` nas variáveis do Render. Não use a chave de desenvolvimento nem a inclua no repositório.

Para persistir os dados em produção, use um banco PostgreSQL gerenciado e configure:

```text
DB_CONNECTION=pgsql
DB_URL=<Internal Database URL do PostgreSQL no Render>
```

O container executa `php artisan migrate --force` ao iniciar. Não use o SQLite local como armazenamento de produção: o sistema de arquivos do serviço pode ser efêmero e os dados podem ser perdidos em novos deploys.

## Executar localmente (sem Docker)

Requisitos:

- Git
- PHP 8.3+, Composer 2 e extensões PHP `mbstring`, `fileinfo`, `openssl`, `pdo`, `pdo_sqlite`, `xml`, `dom` e `curl`
- Node.js 20.19+ ou 22.12+, com npm
- Para usar MySQL: extensão PHP `pdo_mysql` e um servidor MySQL acessível

Confira as versões e extensões com:

```sh
php -v
composer -V
node -v
npm -v
php -m
```

Instale as extensões para a mesma versão do PHP usada pelo terminal. `mbstring` e `pdo_sqlite` são especialmente necessárias para os comandos Laravel, o banco padrão e os testes.

## Instalação local

O projeto usa SQLite por padrão. O comando de configuração instala as dependências, cria `.env` a partir de `.env.example`, gera a chave da aplicação, prepara o banco, executa as migrações e compila os arquivos do frontend:

```sh
composer run setup
```

Inicie a aplicação:

```sh
composer run dev
```

Abra `http://localhost:8000` no navegador. Para encerrar os processos, pressione `Ctrl+C`.

### Executar os processos separadamente

Depois de `composer run setup`, abra dois terminais na pasta do projeto. No primeiro, rode o servidor Laravel:

```sh
php artisan serve
```

No segundo, inicie o Vite:

```sh
npm run dev
```

Acesse `http://localhost:8000`. Encerre cada processo com `Ctrl+C` no terminal correspondente.

### Windows PowerShell

Os comandos `git clone`, `composer run setup` e `composer run dev` também funcionam no PowerShell, desde que PHP, Composer, Node.js e npm estejam no `PATH`. Na configuração manual, copie o arquivo de ambiente com:

```powershell
Copy-Item .env.example .env
```

### Usar MySQL

Crie um banco vazio e configure `.env` antes de executar as migrações. Exemplo:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=projeto_pessoas
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

Como `composer run setup` aplica as migrações usando a configuração atual, para MySQL siga o fluxo manual: instale as dependências, copie `.env`, edite as variáveis acima e então gere a chave, migre e compile:

```sh
composer install
cp .env.example .env
# Edite .env com os dados do seu banco MySQL
php artisan key:generate
php artisan migrate --seed
npm install
npm run build
composer run dev
```

No PowerShell, use `Copy-Item .env.example .env` no lugar de `cp`.

## Comandos úteis

```sh
php artisan migrate
php artisan test
npm run build
```

`php artisan migrate:fresh --seed` recria todas as tabelas e apaga os dados existentes. Use-o somente em um banco descartável.

## Assistentes de código

Os assistentes ajudam a navegar e alterar o código, mas não substituem o servidor. A instalação e a execução são iguais com ou sem um assistente: use `composer run setup` e `composer run dev`.

- **GitHub Copilot no VS Code:** abra a pasta clonada no VS Code e use o modo Agent. As instruções do projeto estão em `.github/copilot-instructions.md` e `AGENTS.md`.
- **Claude Code:** instale-o conforme a [documentação oficial](https://docs.anthropic.com/en/docs/claude-code/overview), abra um terminal na pasta do projeto e execute `claude`. As instruções específicas estão em `CLAUDE.md`.
- **Outros agentes:** abra a raiz do repositório; `AGENTS.md` contém a arquitetura, as convenções e os comandos de validação.

O uso de assistentes é opcional. O projeto pode ser instalado e executado apenas pelo terminal.

## Estrutura principal

- `app/Http/Controllers` e `app/Http/Requests`: operações e validações HTTP
- `app/Models`: modelos Eloquent
- `database/migrations` e `database/seeders`: estrutura e dados iniciais do banco
- `resources/js/Pages`: páginas Vue renderizadas pelo Inertia
- `routes/web.php`: rotas da aplicação
- `tests`: testes automatizados
