<?php

declare(strict_types=1);

namespace Thesis\Result\ColumnTypeRegistry;

use Thesis\Result\ColumnType;
use Thesis\Result\ColumnTypeRegistry;

final class NullColumnTypeRegistry implements ColumnTypeRegistry
{
    public function get(string $columnType): ?ColumnType
    {
        return null;
    }
}
