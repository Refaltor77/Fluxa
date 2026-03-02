<?php

declare(strict_types=1);

namespace App\Middleware;

use Closure;
use Fluxa\Exceptions\HttpException;
use Fluxa\Http\Request;
use Fluxa\Http\Response;
use Fluxa\Middleware\MiddlewareInterface;

final class AuthMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('authorization');

        if ($token === '') {
            throw new HttpException(401, 'Unauthorized');
        }

        return $next($request);
    }
}
