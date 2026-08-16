<?php

use App\Http\Middleware\EnsureAccountRole;
use App\Http\Middleware\EnsureStoreProfileExists;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
            'account_role' => EnsureAccountRole::class,
            'store_profile_exists' => EnsureStoreProfileExists::class,
        ]);

        // A logged-out (or session-expired) hit on a /customer/... route
        // should bounce to the Customer's own OTP login, not the generic
        // Admin/Store password login the `auth` middleware defaults to.
        $middleware->redirectGuestsTo(fn (Request $request) => $request->is('customer/*')
            ? route('customer.login')
            : route('login'));

        // The reverse case: an already-logged-in Customer/Captain landing
        // back on a guest-only login route would otherwise fall through to
        // the generic /dashboard route, which itself redirects on to the
        // right panel — this just skips that extra hop and sends each one
        // straight there.
        $middleware->redirectUsersTo(fn (Request $request) => match (auth()->user()?->role) {
            'customer' => route('customer.prescriptions.index'),
            'captain' => route('captain.dashboard'),
            default => route('dashboard'),
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
