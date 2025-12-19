<!-- Copilot instructions for this Laravel-based API project -->
# Copilot / AI assistant instructions

This repository is a small Laravel 12 API/web skeleton. The guidance below focuses on the concrete, discoverable patterns and workflows that make an AI coding agent productive here.

- **Quick summary:** Laravel 12 skeleton. App code lives in `app/`, HTTP routes are wired in `bootstrap/app.php` and `routes/web.php`, Eloquent models in `app/Models`, migrations in `database/migrations`, and frontend assets are built with Vite (`package.json`).

**Architecture & important files**
- `bootstrap/app.php`: the application is configured here; routing is enabled with `withRouting(...)` and a health endpoint is configured at `/up`.
- `routes/web.php`: web routes live here (example: single root route). Add new web routes here or add files and register them through `bootstrap/app.php` routing config.
- `app/Http/Controllers/`: controllers extend `App\\Http\\Controllers\\Controller`.
- `app/Models/`: Eloquent models (e.g. `app/Models/User.php`). Note: `User.php` uses a non-standard `protected function casts(): array` method instead of a `$casts` property — be careful when reading/writing cast rules in this project.
- `database/migrations/`, `database/factories/`, `database/seeders/`: migrations, model factories and seeders.
- `composer.json`: shows required PHP version (^8.2) and project scripts (`setup`, `dev`, `test`). Use these scripts for consistent behavior.
- `package.json`: Vite, Tailwind and `npm` scripts (`dev`, `build`) for frontend assets.
- `phpunit.xml`: test runner configuration. Tests run with `DB_CONNECTION=sqlite` and `DB_DATABASE=:memory:` by default (no external DB required for tests).

**Developer workflows (explicit commands)**
- Bootstrap project (recommended full setup):

```powershell
composer run setup
```

This runs composer install, copies `.env` from `.env.example`, generates app key, runs migrations, then installs Node deps and builds assets (see `composer.json` `setup` script).

- Run development environment (server, queue worker, Vite dev):

```powershell
composer run dev
```

This runs `php artisan serve`, a queue listener, and `npm run dev` via `npx concurrently` (Windows: PowerShell works with the included scripts). If you only need the API server:

```powershell
php artisan serve
```

- Build assets for production:

```powershell
npm run build
```

- Run tests (uses in-memory sqlite as configured in `phpunit.xml`):

```powershell
composer run test
# or
vendor\\bin\\phpunit
```

**Project-specific conventions & gotchas**
- PHP 8.2 is required (`composer.json`). Use typed properties and return types to match code style.
- `User.php` uses `protected function casts(): array` instead of the usual `$casts` property. Search the repo (`grep`/IDE) before changing cast behavior — tests may rely on this pattern.
- Factories and seeders follow the `Database\\Factories` and `Database\\Seeders` namespaces (see `composer.json` autoload section).
- Background jobs: development script runs `php artisan queue:listen --tries=1`. Tests set `QUEUE_CONNECTION=sync` in `phpunit.xml` so queued jobs run synchronously in CI/test runs.

**Where to make changes**
- Add HTTP controllers to `app/Http/Controllers/` and register routes in `routes/web.php` (or add a new route file and include it via `bootstrap/app.php`).
- Add models in `app/Models/` and corresponding factories in `database/factories/`.
- Add migrations to `database/migrations/` — the project already contains a `2025_12_17_171011_add_role_to_users_table.php` migration as an example.

**Integration points & dependencies**
- Laravel core: `laravel/framework` (see `composer.json`).
- Frontend: Vite + `laravel-vite-plugin`, Tailwind (`package.json`).
- Dev tooling: `concurrently` used in `composer dev` to run multiple processes.

**Testing notes**
- Tests run against an in-memory SQLite DB (see `phpunit.xml`). No local MySQL/Postgres needed for default test runs.
- Use `@php artisan test` or `vendor\\bin\\phpunit` when running tests from CI or locally.

If you want changes merged into an existing `copilot-instructions` file, preserve any project-specific tips already present (especially any notes about non-standard model patterns or custom scripts). No agent docs were found in the repo before this file was created.

Please review these points and tell me any missing project details or developer workflows you'd like added (CI steps, deployment commands, environment secrets, etc.).
