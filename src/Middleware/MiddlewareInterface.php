<?php

declare(strict_types=1);

namespace Fluxa\Middleware;

use Closure;
use Fluxa\Http\Request;
use Fluxa\Http\Response;

interface MiddlewareInterface
{
    public function handle(Request $request, Closure $next): Response;
}
