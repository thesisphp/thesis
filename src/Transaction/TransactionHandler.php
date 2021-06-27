<?php

declare(strict_types=1);

namespace Thesis\Transaction;

interface TransactionHandler
{
    public function begin(): void;

    public function setIsolationLevel(TransactionIsolationLevel $isolationLevel): void;

    public function commit(): void;

    public function rollback(): void;

    public function savepoint(string $savepoint): void;

    public function releaseSavepoint(string $savepoint): void;

    public function rollbackToSavepoint(string $savepoint): void;
}
