<?php

declare(strict_types=1);

namespace Fluxa\Routing;

use Closure;

final readonly class Route
{
    /** @param list<string> $middleware */
    public function __construct(
        public string $method,
        public string $pattern,
        public Closure|array $handler,
        public array $middleware = [],
    ) {}

    public function matches(string $method, string $path): ?array
    {
        if ($this->method !== $method) {
            return null;
        }

        $regex = preg_replace('#\{(\w+)\}#', '(?P<$1>[^/]+)', $this->pattern);
        $regex = '#^' . $regex . '$#';

        if (preg_match($regex, $path, $matches)) {
            return array_filter($matches, fn(string $key) => !is_numeric($key), ARRAY_FILTER_USE_KEY);
        }

        return null;
    }
}
