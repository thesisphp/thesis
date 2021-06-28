<?php

declare(strict_types=1);

namespace Thesis\StatementContext\ValueResolver;

use PHPUnit\Framework\TestCase;
use Thesis\StatementContext\Tsx;
use Thesis\StatementContext\ValueResolverRegistry\InMemoryValueResolverRegistry;
use function PHPUnit\Framework\assertSame;

/**
 * @internal
 * @covers \Thesis\StatementContext\ValueResolver\IdentifierResolver
 * @group unit
 */
final class IdentifierResolverTest extends TestCase
{
    public function testResolveSimpleIdentifier(): void
    {
        $valueResolvers = new InMemoryValueResolverRegistry([
            new IdentifierResolver(),
        ]);

        [$tsx, $parameters] = Tsx::resolve(static fn (Tsx $tsx): string => $tsx(new Identifier('usr')), $valueResolvers);

        assertSame('"usr"', $tsx);
        assertSame([], $parameters);
    }

    public function testResolveIdentifierWithEscapeCharacter(): void
    {
        $valueResolvers = new InMemoryValueResolverRegistry([
            new IdentifierResolver(),
        ]);

        [$tsx, $parameters] = Tsx::resolve(static fn (Tsx $tsx): string => $tsx(new Identifier('us"r')), $valueResolvers);

        assertSame('"us""r"', $tsx);
        assertSame([], $parameters);
    }

    public function testResolveCompositeIdentifier(): void
    {
        $valueResolvers = new InMemoryValueResolverRegistry([
            new IdentifierResolver(),
        ]);

        [$tsx, $parameters] = Tsx::resolve(static fn (Tsx $tsx): string => $tsx(new Identifier('public.usr')), $valueResolvers);

        assertSame('"public"."usr"', $tsx);
        assertSame([], $parameters);
    }
}
