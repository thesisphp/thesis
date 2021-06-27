<?php

declare(strict_types=1);

namespace Thesis\Transaction;

use Thesis\StatementExecutor\StatementExecutor;

final class SqlTransactionHandler implements TransactionHandler
{
    public function __construct(
        private StatementExecutor $statementExecutor,
    ) {
    }

    public function begin(): void
    {
        $this->statementExecutor->execute('start transaction');
    }

    public function setIsolationLevel(TransactionIsolationLevel $isolationLevel): void
    {
        $this->statementExecutor->execute('set transaction isolation level '.$isolationLevel->toString());
    }

    public function commit(): void
    {
        $this->statementExecutor->execute('commit');
    }

    public function rollback(): void
    {
        $this->statementExecutor->execute('rollback');
    }

    public function savepoint(string $savepoint): void
    {
        $this->statementExecutor->execute('savepoint '.$savepoint);
    }

    public function releaseSavepoint(string $savepoint): void
    {
        $this->statementExecutor->execute('release savepoint '.$savepoint);
    }

    public function rollbackToSavepoint(string $savepoint): void
    {
        $this->statementExecutor->execute('rollback to savepoint '.$savepoint);
    }
}
