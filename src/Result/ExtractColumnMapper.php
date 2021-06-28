<?php

declare(strict_types=1);

namespace Thesis\Result;

/**
 * @internal
 * @psalm-internal Thesis\Result
 */
final class ExtractColumnMapper
{
    private bool $checked = false;

    public function __construct(
        private string|int $column,
        private string $fromMethod,
    ) {
    }

    public function __invoke(mixed $row): mixed
    {
        $this->check($row);

        return $row[$this->column];
    }

    /**
     * @psalm-assert array $row
     */
    private function check(mixed $row): void
    {
        if ($this->checked) {
            return;
        }

        if (!\is_array($row)) {
            throw new \UnexpectedValueException(sprintf(
                'Result::%s(\'%s\') expects row value to be of type array{%2$s: mixed}, got %s.',
                $this->fromMethod,
                $this->column,
                get_debug_type($row),
            ));
        }

        if (!\array_key_exists($this->column, $row)) {
            throw new \UnexpectedValueException(sprintf(
                'Result::%s(\'%s\') expects row array to have offset \'%2$s\', got %s.',
                $this->fromMethod,
                $this->column,
                $row ? "array with offsets '".implode("', '", array_keys($row))."'" : 'empty array',
            ));
        }

        $this->checked = true;
    }
}
