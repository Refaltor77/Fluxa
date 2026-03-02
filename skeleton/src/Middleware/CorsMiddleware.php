<?php

declare(strict_types=1);

namespace App\Middleware;

use Closure;
use Fluxa\Http\Request;
use Fluxa\Http\Response;
use Fluxa\Middleware\MiddlewareInterface;

final class CorsMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        return $response
            ->withHeader('access-control-allow-origin', '*')
            ->withHeader('access-control-allow-methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
            ->withHeader('access-control-allow-headers', 'Content-Type, Authorization');
    }
}
