<?php

declare(strict_types=1);

namespace Fluxa\Exceptions;

use RuntimeException;

final class HttpException extends RuntimeException
{
    public function __construct(
        public readonly int $statusCode,
        string $message = '',
        public readonly array $headers = [],
    ) {
        parent::__construct($message, $statusCode);
    }
}
