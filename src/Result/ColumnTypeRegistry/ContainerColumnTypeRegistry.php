<?php

declare(strict_types=1);

namespace Thesis\Result\ColumnTypeRegistry;

use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Thesis\Result\ColumnType;
use Thesis\Result\ColumnTypeRegistry;

final class ContainerColumnTypeRegistry implements ColumnTypeRegistry
{
    /**
     * @param ContainerInterface<class-string<ColumnType>, ColumnType> $columnTypes
     */
    public function __construct(
        private ContainerInterface $columnTypes,
    ) {
    }

    public function get(string $columnType): ?ColumnType
    {
        try {
            return $this->columnTypes->get($columnType);
        } catch (NotFoundExceptionInterface) {
            return null;
        }
    }
}
