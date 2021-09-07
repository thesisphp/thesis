<?php

declare(strict_types=1);

namespace Thesis\StatementExecutor;

final class ExecutedStatement
{
    /**
     * @var \Traversable<int, array>
     * @psalm-readonly
     */
    public \Traversable $rows;

    /**
     * @psalm-readonly
     */
    public int $affectedRowsNumber;

    /**
     * @psalm-readonly
     */
    public array $debugData = [];

    /**
     * @param \Traversable<int, array> $rows
     */
    public function __construct(
        \Traversable $rows,
        int $affectedRowsNumber,
        array $debugData = [],
    ) {
        $this->rows = $rows;
        $this->affectedRowsNumber = $affectedRowsNumber;
        $this->debugData = $debugData;
    }

    public function withDebugData(array $debugData): self
    {
        $statement = clone $this;
        $statement->debugData = array_merge($statement->debugData, $debugData);

        return $statement;
    }
}
