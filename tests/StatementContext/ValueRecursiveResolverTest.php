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
 * @covers \Thesis\StatementContext\ValueRecursiveResolver
 * @group unit
 */
final class ValueRecursiveResolverTest extends TestCase
{
    public function testResolve(): void
    {
        $parameters = new Parameters();
        $valueRecursiveResolver = new ValueRecursiveResolver(
            new InMemoryValueResolverRegistry([
                new JsonResolver(),
            ]),
            $parameters,
            null,
        );

        $result = $valueRecursiveResolver->resolve(new Json(['test' => 123]));

        assertSame(':p0', $result);
        assertEquals('{"test":123}', $parameters->parameters['p0'] ?? '');
    }

    public function testResolveNamedParameter(): void
    {
        $parameters = new Parameters();
        $valueRecursiveResolver = new ValueRecursiveResolver(
            new InMemoryValueResolverRegistry([
                new JsonResolver(),
            ]),
            $parameters,
            'name',
        );

        $result = $valueRecursiveResolver->resolve(new Json(['test' => 123]));

        assertSame(':name', $result);
        assertEquals('{"test":123}', $parameters->parameters['name'] ?? '');
    }

    public function testResolveByImplementedType(): void
    {
        $stringable = new class() implements \Stringable {
            public function __toString(): string
            {
                return 'test';
            }
        };
        $parameters = new Parameters();
        $valueRecursiveResolver = new ValueRecursiveResolver(
            new InMemoryValueResolverRegistry([
                new StringableResolver(),
            ]),
            $parameters,
            null,
        );

        $result = $valueRecursiveResolver->resolve($stringable);

        assertSame(':p0', $result);
        assertEquals('test', $parameters->parameters['p0'] ?? '');
    }

    public function testResolveByExtendedType(): void
    {
        $extendedType = new class() extends \Exception {
            public function __toString(): string
            {
                return 'test';
            }
        };
        $parameters = new Parameters();
        $valueRecursiveResolver = new ValueRecursiveResolver(
            new InMemoryValueResolverRegistry([
                new ExceptionResolver(),
            ]),
            $parameters,
            null,
        );

        $result = $valueRecursiveResolver->resolve($extendedType);

        assertSame(':p0', $result);
        assertEquals('test', $parameters->parameters['p0'] ?? '');
    }

    public function testExceptionRaisedWhenTypeResolutionCycleDetected(): void
    {
        $valueRecursiveResolver = new ValueRecursiveResolver(
            new InMemoryValueResolverRegistry([
                new CyclicStdClassResolver(),
            ]),
            new Parameters(),
            null,
        );

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Type resolution cycle stdClass (Thesis\StatementContext\CyclicStdClassResolver) > stdClass (Thesis\StatementContext\CyclicStdClassResolver) detected.');

        $valueRecursiveResolver->resolve(new \stdClass());
    }
}
