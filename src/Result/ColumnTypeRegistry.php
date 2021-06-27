<?php

declare(strict_types=1);

namespace Thesis\Result;

interface ColumnTypeRegistry
{
    /**
     * @param class-string<ColumnType> $columnType
     */
    public function get(string $columnType): ?ColumnType;
}
