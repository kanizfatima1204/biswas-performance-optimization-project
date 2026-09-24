# Performance engineering audit

## Scope and selected website

The selected site is the self-built Biswas Performance Lab demo in this repository. The audit findings below come from source inspection. There are no captured Lighthouse reports or deploy URL in this checkout, so there is no honest measured baseline or measured before/after result to report yet.

## Initial report status

The database seeder includes illustrative values to populate the comparison UI. They are not observations of either a real client site or this application's Lighthouse run. Treat them only as placeholder UI data; do not use them as project results. The checklist in `SUBMISSION.md` and the report UI label this status.

Capture and retain Lighthouse mobile and desktop reports for the same URL, browser version, and throttling profile before/after changes. Record score, LCP, FCP, CLS, TBT, TTFB, transfer size and request count, plus the report file path and environment in each row's notes. Use multiple runs and report the median if results vary materially. For a valid before/after comparison, use a preserved pre-optimization build or a comparable baseline commit; do not call synthetic seeded rows a baseline.

The report command is `php artisan performance:record`. Example:

```powershell
php artisan performance:record "After optimization" mobile 91 1800 950 0.02 100 260 720 34 --notes="Measured with Lighthouse; report: reports/mobile-after.html; Chrome version, throttling, URL"
```

Delete the four seeder rows before capturing the final comparison. The report page groups rows by device and label and expects one row for each of `Before optimization` and `After optimization`.

## Problems found by source inspection

- The landing page displayed hard-coded performance scores and improvements without evidence.
- An image preload targeted `public/images/hero.svg`, but no page rendered that image, creating unnecessary network work.
- The report checklist claimed lazy-loaded and responsive images that were not used by the current page.
- The seed metrics could be mistaken for real test results.
- The application middleware cannot set cache headers on static files that the web server serves directly.
- This checkout has no `.git` metadata, GitHub remote, or public deployment URL.

These are code/project audit findings, not Lighthouse diagnostics. The source currently has no external raster images on the home page, so image format conversion and responsive image sizing have no applicable payload to optimize. `LazyImage.vue` is an unused component; it should only be wired into the page when actual below-fold raster imagery is introduced.

## Optimization plan and implementation

1. Remove unsubstantiated performance figures and label unmeasured UI as pending.
2. Remove the unused image preload.
3. Make the audit checklist describe implemented behavior only.
4. Keep Vite production minification, fingerprinted output, CSS splitting and dependency chunks.
5. Cache the small home feature list for five minutes.
6. Keep the layout responsive and avoid adding large chart/image dependencies.
7. Capture controlled Lighthouse baseline and optimized runs, then replace sample database rows.
8. Configure immutable caching for fingerprinted static assets at the serving web server/CDN and verify response headers in the deployed environment.

## Mobile performance analysis

The interface has a 320px minimum viewport width, a responsive breakpoint that hides desktop links and exposes the mobile navigation, and a single-column layout on small screens. The page avoids external image downloads and animation/chart libraries. These source-level characteristics are not a substitute for mobile emulation measurements. Record mobile Lighthouse screenshots/report, transfer size, request count, LCP, CLS and TBT after the app is reachable in a production environment.

## Code changes and technical explanation

- `resources/js/Pages/Home.vue`: removed unsupported score claims and an unused SVG preload; the home screen now says measurement is pending.
- `resources/js/Pages/Performance/Index.vue`: marks sample rows as sample data and removes checklist claims about unused image behavior.
- `app/Http/Controllers/HomeController.php`: describes actual Vite, responsive layout and Laravel-cache behavior.
- `README.md`, `docs/SUBMISSION.md`: list missing evidence and external deliverables plainly.
- `app/Http/Middleware/PerformanceHeaders.php`: adds headers to Laravel responses. Static build files bypass this middleware when served directly, so immutable static caching must be configured in the web server/CDN.

The frontend bundle is built by Vite. Fingerprinted asset filenames permit long-lived immutable caching at the static-file layer. Laravel's cache store memoizes home feature data, avoiding repeated work on subsequent requests until the five-minute entry expires. CSS breakpoints and compact navigation adapt the UI on mobile. Real gains must be demonstrated by repeating the same Lighthouse profile before and after optimization; this repository does not yet contain those measured artifacts.

## Local verification limits

At audit time, `npm run build` could not start esbuild because Windows returned `spawn EPERM`, and `php artisan route:list` could not load the app because `vendor/autoload.php` is missing. Reinstall dependencies (`npm install` and `composer install`) and rerun those commands in an environment that permits the esbuild process before deployment. No Lighthouse run was possible from this checkout.
