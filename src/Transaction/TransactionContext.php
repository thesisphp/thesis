<?php

declare(strict_types=1);

namespace Thesis\Transaction;

final class TransactionContext
{
    private const NO_TRANSACTION_LEVEL = 0;
    private const MAIN_TRANSACTION_LEVEL = 1;

    private int $level = self::NO_TRANSACTION_LEVEL;

    public function __construct(
        private TransactionHandler $transactionHandler,
    ) {
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     * @template T of mixed|void
     * @param callable(): T $operation
     * @param ?TransactionIsolationLevels::* $isolationLevel
     * @return T
     */
    public function transactionally(callable $operation, ?string $isolationLevel = null)
    {
        ++$this->level;

        $this->begin($isolationLevel);

        try {
            $result = $operation();

            if (!$result instanceof \Generator) {
                $this->commit();

                return $result;
            }
        } catch (\Throwable $exception) {
            $this->rollback();

            throw $exception;
        } finally {
            --$this->level;
        }

        ++$this->level;

        return $this->transactionalizedGenerator($result);
    }

    /**
     * @psalm-suppress InvalidReturnType, MixedReturnStatement https://github.com/vimeo/psalm/issues/5549
     * @template T of \Generator
     * @param T $generator
     * @throws \Throwable
     * @return T
     */
    private function transactionalizedGenerator(\Generator $generator): \Generator
    {
        try {
            $result = yield from $generator;
            $this->commit();

            return $result;
        } catch (\Throwable $exception) {
            $this->rollback();

            throw $exception;
        } finally {
            --$this->level;
        }
    }

    /**
     * @param ?TransactionIsolationLevels::* $isolationLevel
     */
    private function begin(?string $isolationLevel): void
    {
        if ($this->level === self::MAIN_TRANSACTION_LEVEL) {
            $this->transactionHandler->begin();

            if ($isolationLevel !== null) {
                $this->transactionHandler->setIsolationLevel($isolationLevel);
            }

            return;
        }

        $this->transactionHandler->savepoint($this->levelSavepoint());
    }

    private function commit(): void
    {
        if ($this->level === self::MAIN_TRANSACTION_LEVEL) {
            $this->transactionHandler->commit();

            return;
        }

        $this->transactionHandler->releaseSavepoint($this->levelSavepoint());
    }

    private function rollback(): void
    {
        if ($this->level === self::MAIN_TRANSACTION_LEVEL) {
            $this->transactionHandler->rollback();

            return;
        }

        $this->transactionHandler->rollbackToSavepoint($this->levelSavepoint());
    }

    /**
     * @return literal-string
     */
    private function levelSavepoint(): string
    {
        /** @var literal-string This is not correct from static analysis perspective, but technically concatenating literal with an integer is safe */
        return 'thesis_savepoint_'.$this->level;
    }
}
