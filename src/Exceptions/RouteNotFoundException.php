<?php

declare(strict_types=1);

namespace Fluxa\Exceptions;

final class RouteNotFoundException extends HttpException
{
    public function __construct(string $method, string $path)
    {
        parent::__construct(404, "Route not found: {$method} {$path}");
    }
}
