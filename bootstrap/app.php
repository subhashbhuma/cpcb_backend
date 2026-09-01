<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

if (function_exists('header_remove')) {
    header_remove('X-Powered-By');
    header_remove('Server');
}

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        \App\Providers\RouteConstraints::class,
    ])
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        api: __DIR__ . '/../routes/api.php',
        apiPrefix: 'api',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);
        $middleware->append(\App\Http\Middleware\TrustProxies::class);
        $middleware->append(\App\Http\Middleware\TrustHosts::class);
        $middleware->append(\Illuminate\Http\Middleware\HandleCors::class);

        $middleware->validateCsrfTokens(except: [
            'translate',
            'api/*'
        ]);

        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'setLocale' => \App\Http\Middleware\SetLocale::class,
            'CheckConcurrentLogin' => \App\Http\Middleware\CheckConcurrentLogin::class,
            'check.menu.permission' => \App\Http\Middleware\CheckMenuPermission::class,
            'preventBackHistory' => \App\Http\Middleware\PreventBackHistory::class,
            'referer.check' => \App\Http\Middleware\ValidateReferer::class,
            'scanUploadedFiles' => \App\Http\Middleware\ScanUploadedFiles::class,
            'otp.throttle.generation' => \App\Http\Middleware\OtpGenerationThrottle::class,
            'otp.throttle.verification' => \App\Http\Middleware\OtpVerificationThrottle::class,
            'otp.ratelimit' => \App\Http\Middleware\OtpRateLimitMiddleware::class,
            'geoFence' => \App\Http\Middleware\GeoFencing::class,
            'checkPasswordExpiry' => \App\Http\Middleware\CheckPasswordExpiry::class,
        ]);

        // CSP headers only matter for HTML responses, not JSON APIs
        $middleware->web(append: [
            \Spatie\Csp\AddCspHeaders::class,
        ]);

        $middleware->api(append: [
            \App\Http\Middleware\CacheResponse::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
