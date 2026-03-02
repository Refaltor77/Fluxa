<?php

declare(strict_types=1);

namespace Fluxa\Routing;

final class RouteBuilder
{
    public function __construct(
        private readonly Router $router,
        private readonly int $index,
    ) {}

    public function middleware(string ...$middleware): self
    {
        $route = $this->router->getRoute($this->index);
        $this->router->updateRoute($this->index, new Route(
            $route->method,
            $route->pattern,
            $route->handler,
            array_merge($route->middleware, $middleware),
        ));
        return $this;
    }
}
