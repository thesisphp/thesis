<?php

declare(strict_types=1);

namespace Thesis\Transaction;

final class SqlTransactionHandler implements TransactionHandler
{
    /**
     * @param callable(string): void $statementExecutor
     */
    public function __construct(
        private $statementExecutor,
    ) {
    }

    public function begin(): void
    {
        ($this->statementExecutor)('start transaction');
    }

    public function setIsolationLevel(string $isolationLevel): void
    {
        ($this->statementExecutor)('set transaction isolation level '.$isolationLevel);
    }

    public function commit(): void
    {
        ($this->statementExecutor)('commit');
    }

    public function rollback(): void
    {
        ($this->statementExecutor)('rollback');
    }

    public function savepoint(string $savepoint): void
    {
        ($this->statementExecutor)('savepoint '.$savepoint);
    }

    public function releaseSavepoint(string $savepoint): void
    {
        ($this->statementExecutor)('release savepoint '.$savepoint);
    }

    public function rollbackToSavepoint(string $savepoint): void
    {
        ($this->statementExecutor)('rollback to savepoint '.$savepoint);
    }
}
