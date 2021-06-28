<?php

declare(strict_types=1);

namespace Thesis\StatementContext\ValueResolverRegistry;

use PHPUnit\Framework\TestCase;
use Thesis\StatementContext\ValueResolver;
use Thesis\StatementContext\ValueResolver\Json;
use Thesis\StatementContext\ValueResolver\JsonResolver;
use Thesis\Tool\PsrContainer\ArrayContainer;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertSame;

/**
 * @internal
 * @covers \Thesis\StatementContext\ValueResolverRegistry\ContainerValueResolverRegistry
 * @group unit
 */
final class ContainerValueResolverRegistryTest extends TestCase
{
    public function testGet(): void
    {
        /** @var ArrayContainer<string, ValueResolver> */
        $valueResolvers = new ArrayContainer([Json::class => new JsonResolver()]);
        $valueResolverRegistry = new ContainerValueResolverRegistry($valueResolvers);

        $result = $valueResolverRegistry->get(Json::class);

        assertEquals(new JsonResolver(), $result);
    }

    public function testGetNullReturnedWhenPlaceholderNotFound(): void
    {
        $valueResolverRegistry = new ContainerValueResolverRegistry(new ArrayContainer());

        $result = $valueResolverRegistry->get(Json::class);

        assertSame(null, $result);
    }
}
