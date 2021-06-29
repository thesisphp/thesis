<?php

declare(strict_types=1);

namespace Thesis\Transaction;

/**
 * @psalm-immutable
 */
final class TransactionIsolationLevels
{
    public const READ_UNCOMMITTED = 'read uncommitted';
    public const READ_COMMITTED = 'read committed';
    public const REPEATABLE_READ = 'repeatable read';
    public const SERIALIZABLE = 'serializable';

    private function __construct()
    {
    }
}
