<?php

declare(strict_types=1);

namespace Fluxa\Middleware;

use Closure;
use Fluxa\Container\Container;
use Fluxa\Http\Request;
use Fluxa\Http\Response;

final readonly class Pipeline
{
    public function __construct(private Container $container) {}

    /**
     * @param list<string> $middlewareClasses
     */
    public function run(Request $request, array $middlewareClasses, Closure $destination): Response
    {
        $pipeline = array_reduce(
            array_reverse($middlewareClasses),
            function (Closure $next, string $middlewareClass): Closure {
                return function (Request $request) use ($next, $middlewareClass): Response {
                    /** @var MiddlewareInterface $middleware */
                    $middleware = $this->container->get($middlewareClass);
                    return $middleware->handle($request, $next);
                };
            },
            $destination,
        );

        return $pipeline($request);
    }
}
