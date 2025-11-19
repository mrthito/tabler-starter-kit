<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use App\Http\Middleware\EnsureUserVerifiedTwoFactorAuthMiddleware;
use App\Http\Middleware\RateLimitMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: [
            __DIR__ . '/../routes/web.php',
            __DIR__ . '/../routes/auth.php',
            __DIR__ . '/../routes/admin/web.php',
            __DIR__ . '/../routes/admin/auth.php'
        ],
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(function (Request $request): string {
            if ($request->routeIs('admin.*')) {
                return route('admin.login', absolute: false);
            }
            return route('login');
        })->redirectUsersTo(function (Request $request): string {
            if ($request->routeIs('admin.*')) {
                return route('admin.dashboard', absolute: false);
            }
            return route('dashboard');
        })
        ->alias([
            '2fa' => EnsureUserVerifiedTwoFactorAuthMiddleware::class,
            'ratelimit' => RateLimitMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
