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
        $this->log('Begin transaction.');

        $this->transactionHandler->begin();
    }

    public function setIsolationLevel(string $isolationLevel): void
    {
        $this->log('Set isolation level.');

        $this->transactionHandler->setIsolationLevel($isolationLevel);
    }

    public function commit(): void
    {
        $this->log('Commit.');

        $this->transactionHandler->commit();
    }

    public function rollback(): void
    {
        $this->log('Rollback.');

        $this->transactionHandler->rollback();
    }

    public function savepoint(string $savepoint): void
    {
        $this->log(sprintf('Savepoint %s.', $savepoint));

        $this->transactionHandler->savepoint($savepoint);
    }

    public function releaseSavepoint(string $savepoint): void
    {
        $this->log(sprintf('Release savepoint %s.', $savepoint));

        $this->transactionHandler->releaseSavepoint($savepoint);
    }

    public function rollbackToSavepoint(string $savepoint): void
    {
        $this->log(sprintf('Rollback to savepoint %s.', $savepoint));

        $this->transactionHandler->rollbackToSavepoint($savepoint);
    }

    private function log(string $message): void
    {
        $this->logger->log($this->level, $message);
    }
}
