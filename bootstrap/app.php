<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Route middleware aliases
        $middleware->alias([
            'school' => App\Http\Middleware\ResolveSchool::class,
            'ensure.school' => App\Http\Middleware\EnsureSchoolContext::class,
            'redirect.tenant.dashboard' => App\Http\Middleware\RedirectTenantUsersFromDashboard::class,  
        ]);

        // Apply ResolveSchool on all web routes so team context is set
        $middleware->appendToGroup('web', [App\Http\Middleware\ResolveSchool::class]);
    })
    ->withProviders([
        App\Providers\AuthServiceProvider::class,
        
    ])
    ->withExceptions(function (Exceptions $exceptions): void {})->create();
