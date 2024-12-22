<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: [__DIR__.'/../routes/web.php', __DIR__.'/../routes/admin.php'],
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'customAuth' => \App\Http\Middleware\CustomAuth::class,
            'customGuest' => \App\Http\Middleware\CustomGuest::class,
            'visitor' => \App\Http\Middleware\Visitor::class,
        ]);
        $middleware->validateCsrfTokens([
            '/chatbot/webhook',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
