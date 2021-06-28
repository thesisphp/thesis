<?php

declare(strict_types=1);

namespace Thesis\Result;

use PHPUnit\Framework\TestCase;
use Thesis\Result\ColumnType\FloatColumnType;
use Thesis\Result\ColumnTypeRegistry\InMemoryColumnTypeRegistry;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\once;

/**
 * @internal
 * @covers \Thesis\Result\ColumnTypeMapper
 * @group unit
 */
final class ColumnTypeMapperTest extends TestCase
{
    public function testMapWithoutTypeValueWithoutTransformReturned(): void
    {
        $mapper = new ColumnTypeMapper(new InMemoryColumnTypeRegistry([]), []);

        $result = $mapper(['test' => 123]);

        assertSame(['test' => 123], $result);
    }

    public function testMapWithType(): void
    {
        $mapper = new ColumnTypeMapper(new InMemoryColumnTypeRegistry([new FloatColumnType()]), ['test' => FloatColumnType::class]);

        $result = $mapper(['test' => '123.12']);

        assertSame(['test' => 123.12], $result);
    }

    public function testMapWithTypeAndNullValueNullReturned(): void
    {
        $mapper = new ColumnTypeMapper(new InMemoryColumnTypeRegistry([new FloatColumnType()]), ['test' => FloatColumnType::class]);

        $result = $mapper(['test' => null]);

        assertSame(['test' => null], $result);
    }

    public function testCachesResolvers(): void
    {
        $columnTypeRegistry = $this->createMock(ColumnTypeRegistry::class);
        $columnTypeRegistry->expects(once())->method('get')->willReturn(new FloatColumnType());
        $rowMapper = new ColumnTypeMapper($columnTypeRegistry, ['test' => FloatColumnType::class]);
        $rowMapper(['test' => '123.23']);

        $rowMapper(['test' => '123.12']);
    }
}
