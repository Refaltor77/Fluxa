<?php

declare(strict_types=1);

namespace Fluxa\Routing;

final readonly class MatchedRoute
{
    /** @param array<string, string> $params */
    public function __construct(
        public Route $route,
        public array $params,
        public array $middleware,
    ) {}
}
