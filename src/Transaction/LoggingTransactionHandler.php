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
        $this->logger->log($this->level, 'begin');

        $this->transactionHandler->begin();
    }

    public function setIsolationLevel(TransactionIsolationLevel $isolationLevel): void
    {
        $this->logger->log($this->level, 'setIsolationLevel');

        $this->transactionHandler->setIsolationLevel($isolationLevel);
    }

    public function commit(): void
    {
        $this->logger->log($this->level, 'commit');

        $this->transactionHandler->commit();
    }

    public function rollback(): void
    {
        $this->logger->log($this->level, 'rollback');

        $this->transactionHandler->rollback();
    }

    public function savepoint(string $savepoint): void
    {
        $this->logger->log($this->level, 'savepoint');

        $this->transactionHandler->savepoint($savepoint);
    }

    public function releaseSavepoint(string $savepoint): void
    {
        $this->logger->log($this->level, 'releaseSavepoint');

        $this->transactionHandler->releaseSavepoint($savepoint);
    }

    public function rollbackToSavepoint(string $savepoint): void
    {
        $this->logger->log($this->level, 'rollbackToSavepoint');

        $this->transactionHandler->rollbackToSavepoint($savepoint);
    }
}
