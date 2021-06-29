<?php

declare(strict_types=1);

namespace Thesis\StatementExecutor;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

final class LoggingStatementExecutor implements StatementExecutor
{
    /**
     * @param LogLevel::* $level
     */
    public function __construct(
        private StatementExecutor $statementExecutor,
        private LoggerInterface $logger,
        private string $level = LogLevel::DEBUG,
    ) {
    }

    public function execute(string $statement, array $parameters = [], bool $debug = false): ExecutedStatement
    {
        $this->log($statement, $parameters);

        $executedStatement = $this->statementExecutor->execute($statement, $parameters, $debug);

        if ($debug) {
            $this->log($executedStatement->debugMessage, [
                'affected_rows_number' => $executedStatement->affectedRowsNumber,
            ] + $executedStatement->debugContext);
        }

        return $executedStatement;
    }

    private function log(string $message, array $context): void
    {
        $this->logger->log($this->level, $message, $context);
    }
}
