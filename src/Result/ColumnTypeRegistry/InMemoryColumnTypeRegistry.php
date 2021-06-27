<?php

declare(strict_types=1);

namespace Thesis\Result\ColumnTypeRegistry;

use Thesis\Result\ColumnType;
use Thesis\Result\ColumnTypeRegistry;

final class InMemoryColumnTypeRegistry implements ColumnTypeRegistry
{
    /**
     * @var array<class-string<ColumnType>, ColumnType>
     */
    private array $columnTypes = [];

    /**
     * @param iterable<ColumnType> $columnTypes
     */
    public function __construct(
        iterable $columnTypes,
    ) {
        foreach ($columnTypes as $columnType) {
            $this->columnTypes[$columnType::class] = $columnType;
        }
    }

    public function get(string $columnType): ?ColumnType
    {
        return $this->columnTypes[$columnType] ?? null;
    }
}
