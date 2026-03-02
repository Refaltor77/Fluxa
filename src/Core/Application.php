<?php

declare(strict_types=1);

namespace Fluxa\Core;

use Closure;
use Fluxa\Config\Config;
use Fluxa\Container\Container;
use Fluxa\Exceptions\ErrorHandler;
use Fluxa\Http\Request;
use Fluxa\Http\Response;
use Fluxa\Middleware\Pipeline;
use Fluxa\Routing\Router;
use Throwable;

final class Application
{
    public readonly Container $container;
    public readonly Router $router;
    public readonly Config $config;
    private readonly ErrorHandler $errorHandler;

    public function __construct(string $basePath = '')
    {
        $this->container = new Container();
        $this->router = new Router();
        $this->config = new Config();

        if ($basePath !== '' && is_dir($basePath . '/config')) {
            $this->config->loadDirectory($basePath . '/config');
        }

        $this->errorHandler = new ErrorHandler(
            (bool) $this->config->get('app.debug', false)
        );

        $this->container->instance(self::class, $this);
        $this->container->instance(Container::class, $this->container);
        $this->container->instance(Router::class, $this->router);
        $this->container->instance(Config::class, $this->config);
    }

    public function run(?Request $request = null): Response
    {
        $request ??= Request::capture();

        try {
            $matched = $this->router->resolve($request->method, $request->path);
            $request = $request->withParams($matched->params);

            $pipeline = new Pipeline($this->container);

            return $pipeline->run($request, $matched->middleware, function (Request $req) use ($matched): Response {
                return $this->callHandler($matched->route->handler, $req);
            });
        } catch (Throwable $e) {
            return $this->errorHandler->handle($e);
        }
    }

    public function handle(?Request $request = null): void
    {
        $this->run($request)->send();
    }

    private function callHandler(Closure|array $handler, Request $request): Response
    {
        if ($handler instanceof Closure) {
            $result = $handler($request);
        } else {
            [$class, $method] = $handler;
            $controller = $this->container->get($class);
            $result = $controller->$method($request);
        }

        if ($result instanceof Response) {
            return $result;
        }

        return Response::json($result);
    }
}
