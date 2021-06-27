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

    public function execute(string $statement, array $parameters = []): ExecutedStatement
    {
        $this->logger->log($this->level, 'About to execute statement {statement}.', [
            'statement' => $statement,
            'parameters' => $parameters,
        ]);

        $executedStatement = $this->statementExecutor->execute($statement, $parameters);

        $this->logger->log($this->level, 'Statement was executed and affected {affected_rows_number} rows.', [
            'affected_rows_number' => $executedStatement->affectedRowsNumber,
            'debug_data' => $executedStatement->debugContext,
        ]);

        return $executedStatement;
    }
}
