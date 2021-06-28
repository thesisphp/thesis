<?php

declare(strict_types=1);

namespace Thesis\StatementContext\ValueResolver;

use PHPUnit\Framework\TestCase;
use Thesis\StatementContext\Tsx;
use Thesis\StatementContext\ValueResolverRegistry\InMemoryValueResolverRegistry;
use function PHPUnit\Framework\assertSame;

/**
 * @internal
 * @covers \Thesis\StatementContext\ValueResolver\JsonResolver
 * @group unit
 */
final class JsonResolverTest extends TestCase
{
    public function testResolve(): void
    {
        $valueResolvers = new InMemoryValueResolverRegistry([
            new JsonResolver(),
        ]);

        [$tsx, ['p0' => $parameter]] = Tsx::resolve(static fn (Tsx $tsx): string => $tsx(new Json(['test' => null])), $valueResolvers);

        assertSame(':p0', $tsx);
        assertSame('{"test":null}', $parameter);
    }

    public function testResolveWithJsonForceObject(): void
    {
        $valueResolvers = new InMemoryValueResolverRegistry([
            new JsonResolver(),
        ]);

        [$tsx, ['p0' => $parameter]] = Tsx::resolve(static fn (Tsx $tsx): string => $tsx(new Json([], Json::ALWAYS)), $valueResolvers);

        assertSame(':p0', $tsx);
        assertSame('{}', $parameter);
    }
}
