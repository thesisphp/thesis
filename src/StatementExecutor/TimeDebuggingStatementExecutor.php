<?php

declare(strict_types=1);

namespace Thesis\StatementExecutor;

final class TimeDebuggingStatementExecutor implements StatementExecutor
{
    /**
     * @var callable(): float
     */
    private $microtime;

    /**
     * @param ?callable(): float $microtime For testing purposes
     */
    public function __construct(
        private StatementExecutor $statementExecutor,
        $microtime = null,
    ) {
        $this->microtime = $microtime ?? static fn (): float => microtime(true);
    }

    public function execute(string $statement, array $parameters = [], bool $debug = false): ExecutedStatement
    {
        if (!$debug) {
            return $this->statementExecutor->execute($statement, $parameters, $debug);
        }

        $executionStartTime = ($this->microtime)();
        $executedStatement = $this->statementExecutor->execute($statement, $parameters, $debug);
        $executionTime = (int) round((($this->microtime)() - $executionStartTime) * 1000);

        return new ExecutedStatement(
            $executedStatement->rows,
            $executedStatement->affectedRowsNumber,
            $executedStatement->debugMessage,
            ['time_ms' => $executionTime] + $executedStatement->debugContext,
        );
    }
}
