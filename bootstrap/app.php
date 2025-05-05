<?php

use App\Http\Middleware\EnsureIsAdmin;
use App\Http\Middleware\EnsureIsEmployer;
use App\Http\Middleware\EnsureIsJobSeeker;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(prepend:[
            EnsureFrontendRequestsAreStateful::class,
        ]);

        $middleware->alias([
            'is_admin' => EnsureIsAdmin::class,
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
            
            'employer' => EnsureIsEmployer::class,
            'job_seeker' => EnsureIsJobSeeker::class,
        ]);

        // Create middleware groups
        $middleware->group('admin', [
            'auth:sanctum',
            'is_admin',           
        ]);

        $middleware->group('employer', [
            'auth:sanctum',
            'role:employer',            
        ]);

        $middleware->group('job_seeker', [
            'auth:sanctum',
            'role:job_seeker',            
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
