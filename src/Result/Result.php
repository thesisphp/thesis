<?php

declare(strict_types=1);

namespace Thesis\Result;

use Thesis\Result\ColumnTypeRegistry\InMemoryColumnTypeRegistry;
use Thesis\Result\Hydrator\SimpleHydrator;

/**
 * @template TKey
 * @template TRow
 * @implements \IteratorAggregate<TKey, TRow>
 */
final class Result implements \IteratorAggregate
{
    /**
     * @param \Iterator<TKey, TRow> $rows
     */
    private function __construct(
        private \Iterator $rows,
        public int $affectedRowsNumber,
        private Hydrator $hydrator,
        private ColumnTypeRegistry $columnTypeRegistry,
    ) {
    }

    /**
     * @template TNewKey
     * @template TNewRow
     * @param \Traversable<TNewKey, TNewRow> $rows
     * @return self<TNewKey, TNewRow>
     */
    public static function create(
        \Traversable $rows,
        int $affectedRowsNumber,
        ?Hydrator $hydrator = null,
        ?ColumnTypeRegistry $columnTypeRegistry = null,
    ): self {
        $rows = new \IteratorIterator($rows);
        $rows->rewind();

        return new self(
            new \NoRewindIterator($rows),
            $affectedRowsNumber,
            $hydrator ?? new SimpleHydrator(),
            $columnTypeRegistry ?? new InMemoryColumnTypeRegistry(),
        );
    }

    /**
     * @template TNewKey
     * @param callable(TRow): TNewKey $mapper
     * @return self<TNewKey, TRow>
     */
    public function mapKey(callable $mapper): self
    {
        return new self(
            (function () use ($mapper): \Generator {
                foreach ($this->rows as $row) {
                    yield $mapper($row) => $row;
                }
            })(),
            $this->affectedRowsNumber,
            $this->hydrator,
            $this->columnTypeRegistry,
        );
    }

    /**
     * @template TNewRow
     * @param callable(TRow): TNewRow $mapper
     * @return self<TKey, TNewRow>
     */
    public function mapRow(callable $mapper): self
    {
        return new self(
            (function () use ($mapper): \Generator {
                foreach ($this->rows as $key => $row) {
                    yield $key => $mapper($row);
                }
            })(),
            $this->affectedRowsNumber,
            $this->hydrator,
            $this->columnTypeRegistry,
        );
    }

    /**
     * @param class-string<ColumnType> ...$columnTypes
     */
    public function columnTypes(string ...$columnTypes): self
    {
        return $this->mapRow(new ColumnTypeMapper($this->columnTypeRegistry, $columnTypes));
    }

    /**
     * @throws \UnexpectedValueException If row value is not of type array{$column: mixed}
     * @return self<mixed, TRow>
     */
    public function keyColumn(int|string $column): self
    {
        return $this->mapKey(new ExtractColumnMapper(__METHOD__, $column));
    }

    /**
     * @throws \UnexpectedValueException If row value is not of type array{$column: mixed}
     * @return self<TKey, mixed>
     */
    public function rowColumn(int|string $column): self
    {
        return $this->mapRow(new ExtractColumnMapper(__METHOD__, $column));
    }

    /**
     * @template TNewRow of object
     * @param class-string<TNewRow> $class
     * @return self<TKey, TNewRow>
     */
    public function hydrate(string $class): self
    {
        return $this->mapRow(fn ($row): object => $this->hydrator->hydrate($row, $class));
    }

    /**
     * @throws \TypeError If result key is not of type ?scalar
     * @return (TKey is int|string ? array<TKey, TRow> : array<TRow>)
     */
    public function toArray(): array
    {
        return iterator_to_array($this->rows);
    }

    /**
     * @return list<TRow>
     */
    public function toList(): array
    {
        return iterator_to_array($this->rows, false);
    }

    /**
     * @template TDefault
     * @template TCallable of ?callable(): TDefault
     * @param TCallable $default
     * @return (TCallable is null ? TRow|null : TRow|TDefault)
     */
    public function fetch(?callable $default = null): mixed
    {
        if ($this->rows->valid()) {
            $row = $this->rows->current();
            $this->rows->next();

            return $row;
        }

        if ($default === null) {
            return null;
        }

        return $default();
    }

    /**
     * @return \Iterator<TKey, TRow>
     */
    public function getIterator(): \Iterator
    {
        return $this->rows;
    }
}
