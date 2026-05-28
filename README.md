# Alves Drywall e Pittura — Laravel 12

Site institucional para **Alves Drywall & Pittura** (drywall, pintura e acabamentos), evoluído para incluir **área administrativa completa**, **autenticação**, **gestão de orçamentos** e **integração com Google Calendar**.

## Pitch de 30 segundos (README/LinkedIn)

Desenvolvedor Laravel focado em transformar necessidades de negócio em sistemas web práticos e escaláveis. Neste projeto, evoluí um site institucional para uma plataforma com captação de leads, painel administrativo, workflow comercial por status e integração com Google Calendar para follow-up automático. Trabalho de ponta a ponta (backend, frontend e deploy local com Docker), com foco em organização, confiabilidade e impacto real na operação da empresa.

## Perfil profissional (resumo)

Desenvolvedor focado em **entregar sistemas web completos**, do front ao back, com atenção a experiência do usuário, regras de negócio e manutenção de longo prazo. Neste projeto, transformei um site institucional em uma solução com **captação de leads**, **painel administrativo**, **fluxo comercial com follow-up** e **integração com serviços externos**. Trabalho com mentalidade de produto: não apenas "fazer telas", mas criar funcionalidades que ajudam a operação e aumentam conversão. Tenho base sólida em **Laravel**, **arquitetura MVC**, **integrações API**, **qualidade com testes** e **organização de ambiente com Docker**.

## Resultados que este sistema já gera

- **Centralização do processo comercial**: os pedidos de orçamento ficam organizados em painel único.
- **Menos risco de perder contacto**: follow-up com data/hora e sincronização automática com Google Calendar.
- **Maior rastreabilidade do funil**: status dos preventivi padronizado e fácil de consultar.
- **Produtividade operacional**: listagem paginada, filtros e ações rápidas de atualização/exclusão.
- **Base pronta para escalar**: estrutura separada por camadas, validações e testes de fluxos críticos.

## Como posso contribuir na sua empresa

- **Digitalizar processos manuais** (planilhas, WhatsApp, e-mail solto) em aplicações web organizadas.
- **Construir ou evoluir painéis administrativos** com autenticação, perfis e governança de dados.
- **Integrar sistemas com APIs externas** (agenda, CRM, pagamento, notificações, etc.).
- **Aumentar confiabilidade do produto** com validações, testes e boas práticas de arquitetura.
- **Entregar com visão de negócio**: foco em conversão, operação diária e manutenção sustentável.

## Funcionalidades já entregues (portfolio)

- **Landing page institucional** com apresentação de serviços, identidade visual e CTA para conversão.
- **Captação de leads/orçamentos (`preventivi`)** via formulário com persistência em base de dados.
- **Sistema de recensões** com criação e listagem de avaliações.
- **Backoffice protegido por autenticação** (`login`, `register`, recuperação de senha, verificação de e-mail).
- **Dashboard autenticado** para uso interno e navegação administrativa.
- **Gestão de orçamentos no painel** com listagem paginada, filtros por status, visualização de detalhe, atualização e exclusão.
- **Workflow de status de orçamento** com enum dedicada (`PreventivoStatus`) para padronizar regras de negócio.
- **Follow-up comercial** com data/hora de retorno para leads.
- **Sincronização com Google Calendar**: cria, atualiza e remove eventos conforme alteração de status/follow-up.
- **Perfil do utilizador**: atualização de dados, alteração de password e eliminação de conta.
- **Testes de funcionalidades críticas** (auth, profile e fluxo administrativo) com PHPUnit/Feature Tests.

## Skills demonstradas neste projeto

### Backend (Laravel / PHP)

- **Laravel 12 + arquitetura MVC** (Controllers, Requests, Models, Services, View Components).
- **Design orientado a contratos** com `PreventivoCalendarSync` e implementação concreta/null object.
- **Regras de domínio com Enums** (`PreventivoStatus`) para reduzir inconsistência de estado.
- **Validação robusta com Form Requests** (`UpdatePreventivoRequest`, `LoginRequest`, etc.).
- **Eloquent ORM** (queries, filtros condicionais, paginação, relacionamento com migrations evolutivas).
- **Autenticação e autorização** com middleware (`auth`, `verified`) e fluxo completo Breeze.
- **Tratamento de exceções e fallback gracioso** na integração externa (Google Calendar).
- **Integração REST com API externa** (Google Calendar API) usando credenciais OAuth e sincronização idempotente.
- **Boas práticas de manutenção** com separação de camadas e código preparado para crescimento.

### Frontend (Blade / JS / CSS)

- **Blade templating** com layouts reutilizáveis (`app`, `guest`, `admin`) e componentes.
- **UI administrativa funcional** para operações de negócio (index, show, update, delete).
- **Build moderno com Vite** para assets e fluxo de desenvolvimento rápido.
- **Tailwind CSS + PostCSS + Autoprefixer** para produtividade e consistência visual.
- **JavaScript modular** para comportamento de interface e integração com endpoints.

### Banco de dados e dados

- **Modelagem incremental via migrations**, incluindo evolução de schema para status, notas e follow-up.
- **Seed inicial e suporte a ambiente de desenvolvimento**.
- **Persistência segura de dados de leads/clientes** com validação de entrada.

### Qualidade, DX e DevOps

- **Automação de ambiente** com scripts Composer (`setup`, `dev`, `test`).
- **Ambiente containerizado com Docker Compose** (Laravel app, MySQL, Vite, Mailhog e phpMyAdmin).
- **Debug e observabilidade de desenvolvimento** com Laravel Debugbar e logs.
- **Testes automatizados de regressão** para fluxos principais de autenticação e painel.

## Stack principal

- **PHP 8.2+** + **Laravel 12**
- **MySQL 8**
- **Blade + Vite 7**
- **Tailwind CSS 3 + Alpine.js**
- **PHPUnit 11**
- **Docker + Docker Compose**

## Setup rápido (Docker)

```bash
git clone https://github.com/FernandoAlves78/sito_drywall.git
cd sito_drywall
cp .env.example .env
docker compose up -d --build
```

Quando o Vite estiver pronto (`ready` nos logs), o sistema já pode ser usado.

## URLs locais (Docker)

- Site: `http://localhost:8080`
- Mailhog: `http://localhost:18025`
- phpMyAdmin: `http://localhost:8081`
- Vite HMR: `http://localhost:5173`
- MySQL (host): `localhost:13306` (db `drywall`, user `drywall`, password `secret`)

## Comandos úteis

```bash
# dentro do container app
docker compose exec app php artisan migrate
docker compose exec app php artisan optimize:clear
docker compose exec app php artisan test

# assets
docker compose exec node npm run dev
docker compose exec node npm run build
```

## Rotas principais

### Público

- `GET /` — home institucional
- `POST /preventivo` — envio de pedido de orçamento
- `GET /recensioni` — listagem de recensões
- `POST /recensioni` — criação de recensão

### Área autenticada

- `GET /dashboard` — dashboard
- `GET /preventivi` — listagem de preventivi (admin)
- `GET /preventivi/{preventivo}` — detalhe do preventivo
- `PATCH /preventivi/{preventivo}` — atualização de status/notas/follow-up
- `DELETE /preventivi/{preventivo}` — remoção de preventivo
- `GET|PATCH|DELETE /profile` — gestão de perfil
- Fluxo auth completo em `routes/auth.php` (login, registro, reset e verificação de e-mail)

## Google Calendar

A integração para follow-up comercial está documentada em:

- `docs/GOOGLE_CALENDAR.md`

## Repositório

[github.com/FernandoAlves78/sito_drywall](https://github.com/FernandoAlves78/sito_drywall)

A versão Node/Express original está preservada na tag `v1.0.0`.
