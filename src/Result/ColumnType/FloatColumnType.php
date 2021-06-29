<?php

declare(strict_types=1);

namespace Thesis\Result\ColumnType;

use Thesis\Result\ColumnType;

final class FloatColumnType implements ColumnType
{
    public function transform(mixed $value): ?float
    {
        if ($value === null) {
            return null;
        }

        if (!is_numeric($value)) {
            throw new \UnexpectedValueException(sprintf(
                'Expected numeric or null, got %s.',
                get_debug_type($value),
            ));
        }

        return (float) $value;
    }
}
