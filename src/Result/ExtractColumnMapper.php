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
                'Result::rowColumn(\'%s\') expects row value to be of type array{%1$s: mixed}, got %s.',
                $this->column,
                get_debug_type($row),
            ));
        }

        if (!\array_key_exists($this->column, $row)) {
            throw new \UnexpectedValueException(sprintf(
                'Result::rowColumn(\'%s\') expects row array to have offset \'%1$s\', got %s.',
                $this->column,
                $row ? "array with offsets '".implode("', '", array_keys($row))."'" : 'empty array',
            ));
        }

        $this->checked = true;
    }
}
