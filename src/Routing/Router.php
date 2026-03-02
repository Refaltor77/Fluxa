<?php

declare(strict_types=1);

namespace Fluxa\Routing;

use Closure;
use Fluxa\Exceptions\RouteNotFoundException;

final class Router
{
    /** @var list<Route> */
    private array $routes = [];

    /** @var list<string> */
    private array $globalMiddleware = [];

    public function get(string $pattern, Closure|array $handler): RouteBuilder
    {
        return $this->addRoute('GET', $pattern, $handler);
    }

    public function post(string $pattern, Closure|array $handler): RouteBuilder
    {
        return $this->addRoute('POST', $pattern, $handler);
    }

    public function put(string $pattern, Closure|array $handler): RouteBuilder
    {
        return $this->addRoute('PUT', $pattern, $handler);
    }

    public function patch(string $pattern, Closure|array $handler): RouteBuilder
    {
        return $this->addRoute('PATCH', $pattern, $handler);
    }

    public function delete(string $pattern, Closure|array $handler): RouteBuilder
    {
        return $this->addRoute('DELETE', $pattern, $handler);
    }

    public function middleware(string ...$middleware): void
    {
        array_push($this->globalMiddleware, ...$middleware);
    }

    public function resolve(string $method, string $path): MatchedRoute
    {
        foreach ($this->routes as $route) {
            $params = $route->matches($method, $path);
            if ($params !== null) {
                $middleware = array_merge($this->globalMiddleware, $route->middleware);
                return new MatchedRoute($route, $params, $middleware);
            }
        }

        throw new RouteNotFoundException($method, $path);
    }

    private function addRoute(string $method, string $pattern, Closure|array $handler): RouteBuilder
    {
        $index = count($this->routes);
        $this->routes[] = new Route($method, $pattern, $handler);

        return new RouteBuilder($this, $index);
    }

    public function updateRoute(int $index, Route $route): void
    {
        $this->routes[$index] = $route;
    }

    public function getRoute(int $index): Route
    {
        return $this->routes[$index];
    }

    /** @return list<Route> */
    public function getRoutes(): array
    {
        return $this->routes;
    }
}
