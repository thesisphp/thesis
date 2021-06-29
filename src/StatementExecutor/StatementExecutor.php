<?php

declare(strict_types=1);

namespace Thesis\StatementExecutor;

interface StatementExecutor
{
    public function execute(string $statement, array $parameters = [], bool $debug = false): ExecutedStatement;
}
