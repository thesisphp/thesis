<?php

declare(strict_types=1);

namespace Thesis\StatementContext;

use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertSame;

/**
 * @internal
 * @covers \Thesis\StatementContext\Parameters
 * @group unit
 */
final class ParametersTest extends TestCase
{
    public function testAddUnnamedParameter(): void
    {
        $parameters = new Parameters();

        $result = $parameters->add(null, '123');

        assertSame('p0', $result);
        assertSame('123', $parameters->parameters[$result] ?? '');
    }

    public function testAddNamedParameter(): void
    {
        $parameters = new Parameters();

        $result = $parameters->add('name', '123');

        assertSame('name', $result);
        assertSame('123', $parameters->parameters[$result] ?? '');
    }
}
