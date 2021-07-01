<?php

declare(strict_types=1);

namespace Thesis\StatementExecutor;

interface StatementExecutor
{
    /**
     * @throws StatementExecutionException
     */
    public function execute(string $statement, array $parameters = [], bool $debug = false): ExecutedStatement;
}
