<?php

declare(strict_types=1);

namespace Thesis\StatementExecutor;

use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertNull;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;

/**
 * @internal
 * @covers \Thesis\StatementExecutor\PdoValue
 * @group unit
 */
final class PdoValueTest extends TestCase
{
    public function testFromNull(): void
    {
        $pdoValue = PdoValue::fromMixed(null);

        assertNull($pdoValue->value);
        assertSame(\PDO::PARAM_NULL, $pdoValue->type);
    }

    public function testFromFalse(): void
    {
        $pdoValue = PdoValue::fromMixed(false);

        assertFalse($pdoValue->value);
        assertSame(\PDO::PARAM_BOOL, $pdoValue->type);
    }

    public function testFromTrue(): void
    {
        $pdoValue = PdoValue::fromMixed(true);

        assertTrue($pdoValue->value);
        assertSame(\PDO::PARAM_BOOL, $pdoValue->type);
    }

    public function testFromInt(): void
    {
        $pdoValue = PdoValue::fromMixed(123);

        assertSame(123, $pdoValue->value);
        assertSame(\PDO::PARAM_INT, $pdoValue->type);
    }

    public function testFromFloat(): void
    {
        $pdoValue = PdoValue::fromMixed(1.23);

        assertSame('1.23', $pdoValue->value);
        assertSame(\PDO::PARAM_STR, $pdoValue->type);
    }

    public function testFromString(): void
    {
        $pdoValue = PdoValue::fromMixed('abc');

        assertSame('abc', $pdoValue->value);
        assertSame(\PDO::PARAM_STR, $pdoValue->type);
    }

    public function testFromPdoValue(): void
    {
        $value = new PdoValue('self', \PDO::PARAM_STR);

        $newValue = PdoValue::fromMixed($value);

        assertSame($value, $newValue);
    }

    public function testFromMixedThrowsExceptionForUnknownType(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('PdoStatementExecutor expects value of type null|scalar|PdoValue, stdClass given.');

        PdoValue::fromMixed(new \stdClass());
    }
}
