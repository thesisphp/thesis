<?php

declare(strict_types=1);

namespace Thesis\StatementContext\ValueResolver;

use PHPUnit\Framework\TestCase;
use Thesis\StatementContext\Tsx;
use Thesis\StatementContext\ValueResolverRegistry\InMemoryValueResolverRegistry;
use function PHPUnit\Framework\assertSame;

/**
 * @internal
 * @covers \Thesis\StatementContext\ValueResolver\DateTimeResolver
 * @group unit
 */
final class DateTimeResolverTest extends TestCase
{
    public function testResolve(): void
    {
        $valueResolvers = new InMemoryValueResolverRegistry([
            new DateTimeResolver('YmdHis'),
        ]);
        $dateTime = new \DateTimeImmutable('2021-02-20 14:56:00');

        [$tsx, ['p0' => $parameter]] = Tsx::resolve(static fn (Tsx $tsx): string => $tsx($dateTime), $valueResolvers);

        assertSame(':p0', $tsx);
        assertSame('20210220145600', $parameter);
    }
}
