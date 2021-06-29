<?php

declare(strict_types=1);

namespace Thesis\Transaction;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

final class LoggingTransactionHandler implements TransactionHandler
{
    /**
     * @param LogLevel::* $level
     */
    public function __construct(
        private TransactionHandler $transactionHandler,
        private LoggerInterface $logger,
        private string $level = LogLevel::DEBUG,
    ) {
    }

    public function begin(): void
    {
        $this->logger->log($this->level, 'Begin transaction');

        $this->transactionHandler->begin();
    }

    public function setIsolationLevel(string $isolationLevel): void
    {
        $this->logger->log($this->level, 'Set isolation level');

        $this->transactionHandler->setIsolationLevel($isolationLevel);
    }

    public function commit(): void
    {
        $this->logger->log($this->level, 'Commit');

        $this->transactionHandler->commit();
    }

    public function rollback(): void
    {
        $this->logger->log($this->level, 'Rollback');

        $this->transactionHandler->rollback();
    }

    public function savepoint(string $savepoint): void
    {
        $this->logger->log($this->level, 'Savepoint '.$savepoint);

        $this->transactionHandler->savepoint($savepoint);
    }

    public function releaseSavepoint(string $savepoint): void
    {
        $this->logger->log($this->level, 'Release savepoint '.$savepoint);

        $this->transactionHandler->releaseSavepoint($savepoint);
    }

    public function rollbackToSavepoint(string $savepoint): void
    {
        $this->logger->log($this->level, 'Rollback to savepoint '.$savepoint);

        $this->transactionHandler->rollbackToSavepoint($savepoint);
    }
}
