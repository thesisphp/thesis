<?php

declare(strict_types=1);

namespace Thesis\Result\ColumnType;

use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;

/**
 * @internal
 * @covers \Thesis\Result\ColumnType\JsonColumnType
 * @group unit
 */
final class JsonColumnTypeTest extends TestCase
{
    public function testTransform(): void
    {
        $jsonColumnType = new JsonColumnType();

        $result = $jsonColumnType->transform('{"test":123}');

        assertEquals(['test' => 123], $result);
    }

    public function testTransformFromWrongStringExceptionRaised(): void
    {
        $jsonColumnType = new JsonColumnType();

        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Expected a valid JSON string, got {.');

        $jsonColumnType->transform('{');
    }

    public function testTransformFromWrongValueExceptionRaised(): void
    {
        $jsonColumnType = new JsonColumnType();

        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Expected a valid JSON string or null, got int.');

        $jsonColumnType->transform(123);
    }
}
