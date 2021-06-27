<?php

declare(strict_types=1);

namespace Thesis\Transaction;

/**
 * @psalm-immutable
 */
final class TransactionIsolationLevel
{
    private function __construct(
        private string $isolationLevel,
    ) {
    }

    /**
     * @psalm-pure
     */
    public static function readUncommitted(): self
    {
        return new self('read uncommitted');
    }

    /**
     * @psalm-pure
     */
    public static function readCommitted(): self
    {
        return new self('read committed');
    }

    /**
     * @psalm-pure
     */
    public static function repeatableRead(): self
    {
        return new self('repeatable read');
    }

    /**
     * @psalm-pure
     */
    public static function serializable(): self
    {
        return new self('serializable');
    }

    public function toString(): string
    {
        return $this->isolationLevel;
    }

    public function equals(self $level): bool
    {
        return $this->isolationLevel === $level->isolationLevel;
    }
}
