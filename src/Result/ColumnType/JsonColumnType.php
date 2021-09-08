<?php

declare(strict_types=1);

namespace Thesis\Result\ColumnType;

use Thesis\Result\ColumnType;

final class JsonColumnType implements ColumnType
{
    public function __construct(
        private int $flags = 0,
    ) {
        $this->flags = $this->flags | JSON_THROW_ON_ERROR;
    }

    public function transform(mixed $value): mixed
    {
        if ($value === null) {
            return null;
        }

        if (!\is_string($value)) {
            throw new \UnexpectedValueException(sprintf(
                'Expected a valid JSON string or null, got %s.',
                get_debug_type($value),
            ));
        }

        try {
            return json_decode($value, associative: true, flags: $this->flags);
        } catch (\JsonException $exception) {
            throw new \UnexpectedValueException(
                sprintf('Expected a valid JSON string, got %s.', $value),
                previous: $exception,
            );
        }
    }
}
