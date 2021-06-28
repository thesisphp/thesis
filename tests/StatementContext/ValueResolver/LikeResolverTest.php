<?php

declare(strict_types=1);

namespace Thesis\StatementContext\ValueResolver;

use PHPUnit\Framework\TestCase;
use Thesis\StatementContext\Tsx;
use Thesis\StatementContext\ValueResolverRegistry\InMemoryValueResolverRegistry;
use function PHPUnit\Framework\assertSame;

/**
 * @internal
 * @covers \Thesis\StatementContext\ValueResolver\LikeResolver
 * @group unit
 */
final class LikeResolverTest extends TestCase
{
    /**
     * @dataProvider resolveDataProvider
     */
    public function testResolve(string $escapeCharacter, Like $query, string $expectedValue): void
    {
        $valueResolverRegistry = new InMemoryValueResolverRegistry([
            new LikeResolver($escapeCharacter),
        ]);

        [$tsx, ['p0' => $parameter]] = Tsx::resolve(static fn (Tsx $tsx): string => $tsx($query), $valueResolverRegistry);

        assertSame(":p0 escape '{$escapeCharacter}'", $tsx);
        assertSame($expectedValue, $parameter);
    }

    /**
     * @return \Generator<int, array{string, Like, string}>
     */
    public function resolveDataProvider(): \Generator
    {
        yield ['/', new Like(static fn (LikeEscaper $escaper): string => "%{$escaper->escape('a')}%"), '%a%'];
        yield ['/', new Like(static fn (LikeEscaper $escaper): string => "%{$escaper->escape('%')}%"), '%/%%'];
        yield ['/', new Like(static fn (LikeEscaper $escaper): string => "%{$escaper->escape('_')}%"), '%/_%'];
        yield ['/', new Like(static fn (LikeEscaper $escaper): string => "%{$escaper->escape('/')}%"), '%//%'];
        yield ['!', new Like(static fn (LikeEscaper $escaper): string => "%{$escaper->escape('a')}%"), '%a%'];
        yield ['!', new Like(static fn (LikeEscaper $escaper): string => "%{$escaper->escape('%')}%"), '%!%%'];
        yield ['!', new Like(static fn (LikeEscaper $escaper): string => "%{$escaper->escape('_')}%"), '%!_%'];
        yield ['!', new Like(static fn (LikeEscaper $escaper): string => "%{$escaper->escape('!')}%"), '%!!%'];
    }
}
