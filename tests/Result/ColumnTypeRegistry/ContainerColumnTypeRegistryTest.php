<?php

declare(strict_types=1);

namespace Thesis\Result\ColumnTypeRegistry;

use PHPUnit\Framework\TestCase;
use Thesis\Result\ColumnType;
use Thesis\Result\ColumnType\FloatColumnType;
use Thesis\Tool\PsrContainer\ArrayContainer;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNull;

/**
 * @internal
 * @covers \Thesis\Result\ColumnTypeRegistry\ContainerColumnTypeRegistry
 * @group unit
 */
final class ContainerColumnTypeRegistryTest extends TestCase
{
    public function testGet(): void
    {
        /** @var ArrayContainer<class-string<ColumnType>, ColumnType> */
        $columnTypes = new ArrayContainer([FloatColumnType::class => new FloatColumnType()]);
        $containerColumnTypeRegistry = new ContainerColumnTypeRegistry($columnTypes);

        $result = $containerColumnTypeRegistry->get(FloatColumnType::class);

        assertEquals(new FloatColumnType(), $result);
    }

    public function testGetNullReturnedWhenColumnTypeNotFoundInRegistry(): void
    {
        $containerColumnTypeRegistry = new ContainerColumnTypeRegistry(new ArrayContainer());

        assertNull($containerColumnTypeRegistry->get(FloatColumnType::class));
    }
}
