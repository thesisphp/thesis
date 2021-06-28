<?php

declare(strict_types=1);

namespace Thesis\StatementContext\ValueResolverRegistry;

use PHPUnit\Framework\TestCase;
use Thesis\StatementContext\ValueResolver\Json;
use Thesis\StatementContext\ValueResolver\JsonResolver;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertSame;

/**
 * @internal
 * @covers \Thesis\StatementContext\ValueResolverRegistry\InMemoryValueResolverRegistry
 * @group unit
 */
final class InMemoryValueResolverRegistryTest extends TestCase
{
    public function testGet(): void
    {
        $valueResolverRegistry = new InMemoryValueResolverRegistry([Json::class => new JsonResolver()]);

        $result = $valueResolverRegistry->get(Json::class);

        assertEquals(new JsonResolver(), $result);
    }

    public function testGetNullReturnedWhenValueResolverNotFound(): void
    {
        $valueResolverRegistry = new InMemoryValueResolverRegistry([]);

        $result = $valueResolverRegistry->get(Json::class);

        assertSame(null, $result);
    }
}
