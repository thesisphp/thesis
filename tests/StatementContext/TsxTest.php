<?php

declare(strict_types=1);

namespace Thesis\StatementContext;

use PHPUnit\Framework\TestCase;
use Thesis\StatementContext\ValueResolver\Json;
use Thesis\StatementContext\ValueResolver\JsonResolver;
use Thesis\StatementContext\ValueResolverRegistry\InMemoryValueResolverRegistry;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertSame;

/**
 * @internal
 * @covers \Thesis\StatementContext\Tsx
 * @group unit
 */
final class TsxTest extends TestCase
{
    public function testResolve(): void
    {
        $valueResolverRegistry = new InMemoryValueResolverRegistry([new JsonResolver()]);

        [$tsx, ['p0' => $parameter]] = Tsx::resolve(static fn (Tsx $tsx): string => $tsx(new Json(['test' => 123])), $valueResolverRegistry);

        assertSame(':p0', $tsx);
        assertSame('{"test":123}', $parameter);
    }

    public function testResolveNamed(): void
    {
        $valueResolverRegistry = new InMemoryValueResolverRegistry([new JsonResolver()]);

        [$tsx, ['test' => $parameter]] = Tsx::resolve(static fn (Tsx $tsx): string => $tsx(test: new Json(['test' => 123])), $valueResolverRegistry);

        assertSame(':test', $tsx);
        assertSame('{"test":123}', $parameter);
    }

    public function testResolveMultipleValues(): void
    {
        $valueResolverRegistry = new InMemoryValueResolverRegistry([new JsonResolver()]);

        [$tsx, ['p0' => $parameter1, 'p1' => $parameter2]] = Tsx::resolve(
            static fn (Tsx $tsx): string => $tsx(new Json(['test1' => 123]), new Json(['test2' => 321])),
            $valueResolverRegistry,
        );

        assertSame(':p0, :p1', $tsx);
        assertSame('{"test1":123}', $parameter1);
        assertSame('{"test2":321}', $parameter2);
    }

    public function testResolveString(): void
    {
        [$tsx, $parameters] = Tsx::resolve('test');

        assertSame('test', $tsx);
        assertEquals([], $parameters);
    }
}
