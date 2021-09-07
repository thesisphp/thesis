<?php

declare(strict_types=1);

namespace Thesis\StatementExecutor;

final class ExecutionTimeMeasuringStatementExecutor implements StatementExecutor
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

    public function execute(string $statement, array $parameters = []): ExecutedStatement
    {
        $executionStartTime = ($this->microtime)();
        $executedStatement = $this->statementExecutor->execute($statement, $parameters);
        $executionTime = (int) round((($this->microtime)() - $executionStartTime) * 1000);

        return $executedStatement->withDebugData(['execution_time_ms' => $executionTime]);
    }
}
