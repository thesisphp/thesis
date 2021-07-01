<?php

declare(strict_types=1);

namespace Thesis\StatementExecutor;

abstract class StatementExecutionException extends \Exception
{
    public function __construct(
        string $message = '',
        public int|string $errorCode = 0,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, previous: $previous);
    }
}
