<?php

declare(strict_types=1);

namespace Fluxa\Container;

use Closure;
use Fluxa\Exceptions\ContainerException;
use Fluxa\Exceptions\NotFoundException;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;

final class Container
{
    /** @var array<string, Closure> */
    private array $bindings = [];

    /** @var array<string, Closure> */
    private array $factories = [];

    /** @var array<string, object> */
    private array $singletons = [];

    /** @var array<string, true> */
    private array $singletonKeys = [];

    public function singleton(string $abstract, Closure $factory): void
    {
        $this->singletonKeys[$abstract] = true;
        $this->bindings[$abstract] = $factory;
    }

    public function factory(string $abstract, Closure $factory): void
    {
        $this->factories[$abstract] = $factory;
    }

    public function bind(string $abstract, Closure $factory): void
    {
        $this->bindings[$abstract] = $factory;
    }

    public function instance(string $abstract, object $instance): void
    {
        $this->singletons[$abstract] = $instance;
        $this->singletonKeys[$abstract] = true;
    }

    public function has(string $abstract): bool
    {
        return isset($this->singletons[$abstract])
            || isset($this->bindings[$abstract])
            || isset($this->factories[$abstract]);
    }

    /**
     * @template T of object
     * @param class-string<T>|string $abstract
     * @return T|object
     */
    public function get(string $abstract): object
    {
        if (isset($this->singletons[$abstract])) {
            return $this->singletons[$abstract];
        }

        if (isset($this->factories[$abstract])) {
            return ($this->factories[$abstract])($this);
        }

        if (isset($this->bindings[$abstract])) {
            $instance = ($this->bindings[$abstract])($this);

            if (isset($this->singletonKeys[$abstract])) {
                $this->singletons[$abstract] = $instance;
            }

            return $instance;
        }

        return $this->resolve($abstract);
    }

    public function resolve(string $class): object
    {
        if (!class_exists($class)) {
            throw new NotFoundException("Class '{$class}' not found.");
        }

        $reflection = new ReflectionClass($class);

        if (!$reflection->isInstantiable()) {
            throw new ContainerException("Class '{$class}' is not instantiable.");
        }

        $constructor = $reflection->getConstructor();

        if ($constructor === null) {
            return new $class();
        }

        $dependencies = array_map(
            fn(ReflectionParameter $param) => $this->resolveDependency($param, $class),
            $constructor->getParameters()
        );

        return $reflection->newInstanceArgs($dependencies);
    }

    private function resolveDependency(ReflectionParameter $param, string $forClass): mixed
    {
        $type = $param->getType();

        if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
            return $this->get($type->getName());
        }

        if ($param->isDefaultValueAvailable()) {
            return $param->getDefaultValue();
        }

        throw new ContainerException(
            "Cannot resolve parameter '\${$param->getName()}' for '{$forClass}'."
        );
    }
}
