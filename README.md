# Alves Drywall e Pittura — Site Laravel 12

Site institucional para **Alves Drywall & Pittura** (drywall, pintura e acabamentos), com formulários de orçamento e recensões. Construído em **Laravel 12** com **Blade + Vite**, persistência em **MySQL**, e ambiente de desenvolvimento totalmente em **Docker**.

A UI (HTML/CSS/JS) foi mantida intacta a partir da versão Node original (tag git `v1.0.0`).

## Stack

- **PHP 8.3** + **Laravel 12** (Nginx + PHP-FPM + Supervisord no mesmo container `app`)
- **MySQL 8** como base de dados
- **Node 22** com **Vite 7** para HMR e build de assets
- **Mailhog** para captura de e-mails de desenvolvimento
- **phpMyAdmin** para administração visual da BD

## Requisitos

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) (com Docker Compose v2)

Não precisas de PHP, Composer, Node ou MySQL instalados na máquina — está tudo nos containers.

## Arranque rápido

```bash
git clone https://github.com/FernandoAlves78/sito_drywall.git
cd sito_drywall
cp .env.example .env
docker compose up -d --build
```

Na primeira vez o `node` faz `npm install` automaticamente (~3 min) e arranca o Vite. Para acompanhar:

```bash
docker compose logs -f node
```

Quando aparecer `VITE ... ready` o site está pronto.

## URLs locais

| Serviço | URL |
|---------|-----|
| Site (Laravel) | http://localhost:8080 |
| Mailhog (UI) | http://localhost:18025 |
| phpMyAdmin | http://localhost:8081 |
| Vite HMR | http://localhost:5173 |
| MySQL (host) | `localhost:13306` (user `drywall`, pwd `secret`, db `drywall`) |
| SMTP Mailhog (host) | `localhost:11025` |

> As portas no host foram deslocadas (13306, 11025, 18025) para não chocar com Laragon. Dentro da rede Docker, MySQL/Mailhog continuam a usar as portas standard (`3306`, `1025`, `8025`).

## Comandos comuns

Todos correm dentro do container `app`:

```bash
# Migrations
docker compose exec app php artisan migrate

# Limpar caches
docker compose exec app php artisan optimize:clear

# Tinker
docker compose exec app php artisan tinker

# Composer
docker compose exec app composer install
docker compose exec app composer require pacote/exemplo

# Build de produção dos assets
docker compose exec node npm run build
```

## Estrutura do projecto (parte relevante)

| Caminho | Descrição |
|---------|-----------|
| `app/Http/Controllers/HomeController.php` | Rota `/` — devolve `home.blade.php` |
| `app/Http/Controllers/PreventivoController.php` | `POST /preventivo`, grava em BD e envia email via Mailhog |
| `app/Http/Controllers/RecensioneController.php` | `GET|POST /recensioni` |
| `app/Mail/PreventivoRecebido.php` | Mailable do preventivo |
| `app/Models/Preventivo.php` / `Recensione.php` | Models Eloquent |
| `database/migrations/*_create_preventivi_table.php` | Schema da tabela `preventivi` |
| `database/migrations/*_create_recensioni_table.php` | Schema da tabela `recensioni` |
| `resources/views/home.blade.php` | Página principal (ex-`index.html`) |
| `resources/views/emails/preventivo-recebido.blade.php` | Template HTML do email |
| `resources/css/app.css` | CSS original do site |
| `resources/js/app.js` | JS original (lightbox, fetch para `/preventivo` e `/recensioni`) com CSRF |
| `public/logo.svg`, `public/immagini/` | Assets servidos directamente |
| `routes/web.php` | Rotas |
| `docker/app/Dockerfile` | Imagem PHP 8.3 + Nginx + Supervisord |
| `docker/app/nginx.conf` | Server block Nginx → `public/index.php` |
| `docker/app/php.ini` | Timezone, opcache, limites |
| `docker/app/supervisord.conf` | Gestão de `php-fpm` + `nginx` |
| `docker-compose.yml` | Orquestração dos 5 serviços |

## API

| Método | Rota | Descrição |
|--------|------|-----------|
| `GET` | `/` | Página principal |
| `POST` | `/preventivo` | Cria pedido de orçamento + envia email para Mailhog |
| `GET` | `/recensioni` | Lista recensões aprovadas (JSON) |
| `POST` | `/recensioni` | Cria recensão |

POSTs precisam de header `X-CSRF-TOKEN` (já tratado em `resources/js/app.js`).

## Resetar a base de dados

```bash
docker compose exec app php artisan migrate:fresh
```

Para apagar também o volume MySQL:

```bash
docker compose down -v
```

## Stop / start

```bash
docker compose stop          # pára (mantém dados)
docker compose start         # arranca de novo
docker compose down          # remove containers (mantém volume da BD)
docker compose down -v       # remove containers + volumes
```

## Repositório

[github.com/FernandoAlves78/sito_drywall](https://github.com/FernandoAlves78/sito_drywall)

A versão Node/Express original está preservada na tag git `v1.0.0`.

## Licença

ISC.
