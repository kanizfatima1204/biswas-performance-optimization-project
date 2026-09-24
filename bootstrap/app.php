<?php
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Configuration\Exceptions;
use App\Http\Middleware\PerformanceHeaders;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(web: __DIR__.'/../routes/web.php', commands: __DIR__.'/../routes/console.php', health: '/up')
    ->withMiddleware(function (Middleware $middleware) {
        // Railway terminates HTTPS at its proxy; trust forwarded scheme/host
        // so Laravel and Vite emit HTTPS asset URLs instead of blocked HTTP.
        $middleware->trustProxies(at: '*');
        $middleware->web(append: [PerformanceHeaders::class]);
    })
    ->withExceptions(function (Exceptions $exceptions) {})
    ->create();
