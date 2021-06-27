<?php

declare(strict_types=1);

namespace Thesis\StatementExecutor;

final class ExecutedStatement
{
    /**
     * @param \Traversable<int, array> $rows
     */
    public function __construct(
        public \Traversable $rows,
        public int $affectedRowsNumber,
        public string $debugMessage = '',
        public array $debugContext = [],
    ) {
    }
}
