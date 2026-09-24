# Biswas IT Firm: Website Performance Optimization Demo

This repository contains a self-built Laravel 12, Vue 3 and Inertia demo for the Biswas IT Firm performance engineering assignment. It is the selected website under audit; it is not a third-party client site.

## Deliverables and current status

| Requirement | Status |
|---|---|
| Selected website | This self-built Biswas Performance Lab demo |
| Initial performance report | Not measured yet. See `docs/PERFORMANCE-AUDIT.md` for the report template and test method. |
| Identified problems | See audit document; initial findings are code inspection, not Lighthouse findings. |
| Optimization plan | See audit document. |
| Optimized website | Implemented in this repository; production build must be generated for deployment. |
| Before/after result | Sample seed rows are illustrative only. Replace with real, comparable Lighthouse runs. |
| Mobile analysis | Responsive implementation is documented; mobile Lighthouse evidence is pending. |
| Code changes | See sections below and the audit document. |
| GitHub repository | [biswas-performance-optimization-project](https://github.com/kanizfatima1204/biswas-performance-optimization-project) |
| Live demo | Not deployed. No live URL is available. |
| Technical explanation | See audit document. |

Do not submit the sample figures in the report as measured outcomes. No Lighthouse baseline or optimized run was present in this checkout.

## Run locally

Requirements: PHP 8.2+, Composer, Node.js 20+, and MySQL/MariaDB (or configure another Laravel-supported database).

On XAMPP, enable the ZIP extension in the `php.ini` used by the CLI (`php --ini` shows its path) before installing Composer dependencies. Uncomment or add `extension=zip`, save the file, and verify `php -m` lists `zip`. Composer needs ZIP support or an installed `unzip`/`7z` utility to extract package archives.

```powershell
composer install --prefer-dist
Copy-Item .env.example .env
php artisan key:generate
npm install
```

Set valid database credentials in `.env`, then run the migrations and seed data:

```powershell
php artisan migrate --seed
npm run build
php artisan serve
```

Open `http://127.0.0.1:8000` and `/performance-report`.

## Record real measurements

Use Chrome Lighthouse (or PageSpeed Insights for a deployed URL) with the same URL, browser version, device profile and throttling for baseline and optimized runs. Save the Lighthouse JSON/HTML reports. Record one row per run using:

```powershell
php artisan performance:record "Before optimization" mobile SCORE LCP_MS FCP_MS CLS TBT_MS TTFB_MS TRANSFER_KB REQUESTS --notes="Measured with Lighthouse; report: path/to/report.html; device/network conditions: ..."
```

Use `desktop` for desktop runs and `After optimization` for the optimized label. The report page reads rows from `performance_metrics`. Seeded sample rows are not deleted automatically when adding measurements, so remove the sample rows in the database before capturing the final report. See `docs/PERFORMANCE-AUDIT.md` for details and limitations.

## Implemented performance work

- Vite generates production assets with fingerprinted filenames, CSS splitting and Vue/icon chunks.
- Home feature data is cached for five minutes with Laravel's configured cache store.
- Laravel middleware adds security response headers and a short cache lifetime for the public home response.
- CSS uses responsive breakpoints and a compact mobile navigation.
- The current home page uses CSS and inline SVG icons; it has no external raster-image payload. The unused image preload was removed to avoid an unnecessary request.

Static asset cache headers should be configured at the web server/CDN because static files are served before Laravel middleware. The middleware's static path check does not set headers for files served directly by Nginx/Apache.

## Repository and deployment

The project is published at [biswas-performance-optimization-project](https://github.com/kanizfatima1204/biswas-performance-optimization-project). A public application URL is still pending; do not represent a local URL as a public demo.

### Railway deployment

`railway.json` configures Railpack to build Vite assets, set write permissions on Laravel's cache/storage directories, run migrations before deploy, and health-check Laravel's `/up` endpoint. Tracked `.gitignore` placeholders preserve Laravel's required writable directories in fresh source checkouts. Railway's Laravel Railpack integration supplies the PHP-FPM/Caddy web server. Set `APP_ENV=production`, `APP_DEBUG=false`, a generated `APP_KEY`, and `LOG_CHANNEL=stderr` in the Railway service variables. Connect a Railway MySQL or PostgreSQL service. For PostgreSQL set `DB_CONNECTION=pgsql` and `DB_URL` to the database service URL (for example `${{Postgres.DATABASE_URL}}`); MySQL can use the individual `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` variables or `DB_URL`. The migration pre-deploy step requires a reachable database.
