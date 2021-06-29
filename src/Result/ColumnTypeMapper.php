<?php

declare(strict_types=1);

namespace Thesis\Result;

/**
 * @internal
 * @psalm-internal Thesis\Result
 */
final class ColumnTypeMapper
{
    /**
     * @var ?array<\Closure(array): mixed>
     */
    private ?array $resolvers = null;

    /**
     * @param array<class-string<ColumnType>> $columnTypes
     */
    public function __construct(
        private ColumnTypeRegistry $columnTypeRegistry,
        private array $columnTypes,
    ) {
    }

    public function __invoke(mixed $row): array
    {
        return array_map(
            static fn (\Closure $resolver): mixed => $resolver($row),
            $this->resolvers($row),
        );
    }

    /**
     * @psalm-assert array $row
     * @return array<\Closure(array): mixed>
     */
    private function resolvers(mixed $row): array
    {
        if ($this->resolvers !== null) {
            return $this->resolvers;
        }

        if (!\is_array($row)) {
            throw new \UnexpectedValueException(sprintf(
                'Column types can be mapped only if row is an array, %s given.',
                get_debug_type($row),
            ));
        }

        $this->resolvers = [];

        foreach (array_keys($row) as $column) {
            if (!isset($this->columnTypes[$column])) {
                $this->resolvers[$column] = static fn (array $row): mixed => $row[$column];

                continue;
            }

            $type = $this
                ->columnTypeRegistry
                ->get($this->columnTypes[$column])
                ?? throw new \LogicException(sprintf(
                    'Column type "%s" does not exist.',
                    $this->columnTypes[$column],
                ))
            ;

            $this->resolvers[$column] = static fn (array $row): mixed => $type->transform($row[$column]);
        }

        return $this->resolvers;
    }
}
