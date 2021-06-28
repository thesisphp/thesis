<?php

declare(strict_types=1);

namespace Thesis\Result\ColumnType;

use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertSame;

/**
 * @internal
 * @covers \Thesis\Result\ColumnType\FloatColumnType
 * @group unit
 */
final class FloatColumnTypeTest extends TestCase
{
    public function testTransform(): void
    {
        $floatColumnType = new FloatColumnType();

        $result = $floatColumnType->transform('134.34');

        assertSame(134.34, $result);
    }

    public function testTransformFromWrongValueExceptionRaised(): void
    {
        $floatColumnType = new FloatColumnType();

        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Expected numeric or null, got string.');

        $floatColumnType->transform('test');
    }
}
