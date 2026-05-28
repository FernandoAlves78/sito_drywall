# Alves Drywall e Pittura — Laravel 12

Institutional website for **Alves Drywall & Pittura** (drywall, painting, and finishing), evolved into a full solution with **admin area**, **authentication**, **quote management**, and **Google Calendar integration**.

## 30-Second Pitch (README/LinkedIn)

Laravel developer focused on turning business needs into practical, scalable web systems. In this project, I transformed a marketing website into a platform with lead capture, admin workflows, quote status management, and automatic follow-up via Google Calendar. I work end-to-end (backend, frontend, and Docker-based local environment) with strong focus on reliability, maintainability, and business impact.

## Professional Profile (Summary)

Developer focused on delivering **end-to-end web products**, from frontend to backend, with attention to user experience, business rules, and long-term maintainability. In this project, I turned an institutional website into a business-ready platform with **lead generation**, **admin panel**, **commercial follow-up workflow**, and **external API integration**. I build with product mindset: not just screens, but features that support operations and improve conversion. Strong background in **Laravel**, **MVC architecture**, **API integrations**, **test-driven quality**, and **Docker-based environments**.

## Business Outcomes This System Already Delivers

- **Centralized commercial workflow**: quote requests are organized in a single admin panel.
- **Lower risk of missed follow-ups**: date/time follow-up plus automatic Google Calendar sync.
- **Better funnel visibility**: standardized quote statuses and easy filtering.
- **Operational productivity**: pagination, filters, and fast update/delete actions.
- **Scalable foundation**: layered architecture, validation, and tests for critical flows.

## How I Can Contribute to Your Company

- **Digitize manual workflows** (spreadsheets, WhatsApp threads, scattered emails) into structured web apps.
- **Build or evolve admin platforms** with authentication, profiles, and data governance.
- **Integrate systems with external APIs** (calendar, CRM, payments, notifications, and more).
- **Increase product reliability** with validation, tests, and sound architectural practices.
- **Deliver with business focus**: conversion, day-to-day operations, and sustainable maintenance.

## Delivered Features (Portfolio)

- **Institutional landing page** with services, branding, and conversion-oriented CTAs.
- **Lead/quote capture (`preventivi`)** through form submission with database persistence.
- **Reviews module** with creation and listing of customer reviews.
- **Protected back office** with full auth flows (`login`, `register`, password reset, email verification).
- **Authenticated dashboard** for internal navigation and operations.
- **Admin quote management** with paginated listing, status filters, details, update, and delete.
- **Quote status workflow** using dedicated enum (`PreventivoStatus`) to enforce business consistency.
- **Commercial follow-up scheduling** with dedicated date/time fields.
- **Google Calendar synchronization**: creates, updates, and removes events according to status/follow-up changes.
- **User profile management**: profile update, password change, and account deletion.
- **Critical feature testing** (auth, profile, admin flow) with PHPUnit Feature Tests.

## Skills Demonstrated In This Project

### Backend (Laravel / PHP)

- **Laravel 12 + MVC architecture** (Controllers, Requests, Models, Services, View Components).
- **Contract-oriented design** with `PreventivoCalendarSync` and concrete/null implementations.
- **Domain rules with Enums** (`PreventivoStatus`) to reduce state inconsistency.
- **Robust validation with Form Requests** (`UpdatePreventivoRequest`, `LoginRequest`, etc.).
- **Eloquent ORM usage** (queries, conditional filters, pagination, schema evolution via migrations).
- **Authentication and authorization** through middleware (`auth`, `verified`) and full Breeze flow.
- **Exception handling with graceful fallback** for external integrations (Google Calendar).
- **REST API integration** (Google Calendar API) using OAuth credentials and idempotent sync behavior.
- **Maintainability best practices** with separation of concerns and growth-ready code.

### Frontend (Blade / JS / CSS)

- **Blade templating** with reusable layouts (`app`, `guest`, `admin`) and components.
- **Functional admin UI** for core business operations (index, show, update, delete).
- **Modern asset pipeline with Vite** for fast development and production builds.
- **Tailwind CSS + PostCSS + Autoprefixer** for consistent and productive styling.
- **Modular JavaScript** for UI behavior and endpoint integration.

### Database and Data

- **Incremental data modeling via migrations**, including schema evolution for status, notes, and follow-up.
- **Seed support and development-ready setup**.
- **Secure persistence of lead/customer data** with input validation.

### Quality, DX, and DevOps

- **Environment automation** with Composer scripts (`setup`, `dev`, `test`).
- **Containerized stack with Docker Compose** (Laravel app, MySQL, Vite, Mailhog, phpMyAdmin).
- **Development observability and debugging** with Laravel Debugbar and logs.
- **Automated regression tests** for key authentication and admin workflows.

## Main Stack

- **PHP 8.2+** + **Laravel 12**
- **MySQL 8**
- **Blade + Vite 7**
- **Tailwind CSS 3 + Alpine.js**
- **PHPUnit 11**
- **Docker + Docker Compose**

## Quick Setup (Docker)

```bash
git clone https://github.com/FernandoAlves78/sito_drywall.git
cd sito_drywall
cp .env.example .env
docker compose up -d --build
```

When Vite shows `ready` in the logs, the system is ready to use.

## Local URLs (Docker)

- Site: `http://localhost:8080`
- Mailhog: `http://localhost:18025`
- phpMyAdmin: `http://localhost:8081`
- Vite HMR: `http://localhost:5173`
- MySQL (host): `localhost:13306` (db `drywall`, user `drywall`, password `secret`)

## Useful Commands

```bash
# inside the app container
docker compose exec app php artisan migrate
docker compose exec app php artisan optimize:clear
docker compose exec app php artisan test

# assets
docker compose exec node npm run dev
docker compose exec node npm run build
```

## Main Routes

### Public

- `GET /` - institutional homepage
- `POST /preventivo` - quote request submission
- `GET /recensioni` - reviews listing
- `POST /recensioni` - review creation

### Authenticated Area

- `GET /dashboard` - dashboard
- `GET /preventivi` - preventivi listing (admin)
- `GET /preventivi/{preventivo}` - preventivo detail
- `PATCH /preventivi/{preventivo}` - status/notes/follow-up update
- `DELETE /preventivi/{preventivo}` - preventivo removal
- `GET|PATCH|DELETE /profile` - profile management
- Full auth flow in `routes/auth.php` (login, registration, reset, email verification)

## Google Calendar

Follow-up integration documentation:

- `docs/GOOGLE_CALENDAR.md`

## Repository

[github.com/FernandoAlves78/sito_drywall](https://github.com/FernandoAlves78/sito_drywall)

The original Node/Express version is preserved in git tag `v1.0.0`.
