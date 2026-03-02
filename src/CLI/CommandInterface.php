<?php

declare(strict_types=1);

namespace Fluxa\CLI;

interface CommandInterface
{
    public function name(): string;

    public function description(): string;

    public function execute(array $args): int;
}
