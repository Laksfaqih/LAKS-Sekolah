# Repository Guidelines

## Project Structure & Module Organization
This repository is a Laravel application for school scheduling, attendance, reporting, and automated bells. Core backend code lives in `app/`, with HTTP controllers in `app/Http/Controllers`, domain models in `app/Models`, and shared helpers in `app/Support`. Blade views are in `resources/views`, grouped by role like `admin/`, `guru/`, and `kepsek/`. Frontend assets live in `resources/css` and `resources/js`, and Vite outputs production files to `public/build`. Routes are defined in `routes/`, while database migrations, factories, and seeders live under `database/`. Tests are in `tests/Feature` and `tests/Unit`.

## Build, Test, and Development Commands
Use `composer setup` for first-time setup: installs dependencies, creates `.env`, generates the app key, runs migrations, installs Node packages, and builds assets. Use `composer dev` to run the full local stack: Laravel server, queue listener, log tailing, and Vite. For frontend-only work, use `npm run dev` during development and `npm run build` for production assets. Run tests with `composer test` or `php artisan test`. Scheduler-related work commonly uses `php artisan bells:check` and `php artisan schedule:work`.

## Coding Style & Naming Conventions
Follow `.editorconfig`: UTF-8, LF line endings, spaces, and 4-space indentation for PHP, with 2 spaces only for general YAML files. Format PHP with `./vendor/bin/pint`. Keep PSR-4 class naming under `App\\...`; controllers should end with `Controller`, requests with `Request`, and seeders with `Seeder`. Blade templates should use lowercase kebab-case filenames such as `page-header.blade.php`.

## Testing Guidelines
This project uses Pest with Laravel integration. Add feature coverage for HTTP flows, role access, and scheduler behavior in `tests/Feature`; keep isolated logic tests in `tests/Unit`. Name tests clearly by behavior, for example `AdminCanGenerateScheduleReportTest` or descriptive Pest cases inside an existing file. Run `php artisan test` before opening a PR.

## Commit & Pull Request Guidelines
The local copy does not include `.git`, so commit conventions cannot be inferred from history here. Use short, imperative commit messages such as `Add teacher attendance printing`. PRs should include a concise summary, testing notes, linked issues, and screenshots for Blade UI changes. Call out `.env`, migration, scheduler, or storage-impacting changes explicitly.

## Security & Configuration Tips
Do not commit secrets from `.env`. Development defaults expect MySQL, while backup and restore flows also support SQLite. Uploaded bell audio is stored on the `public` disk under `storage/app/public/bells`; verify storage links and file permissions when working on media features.
