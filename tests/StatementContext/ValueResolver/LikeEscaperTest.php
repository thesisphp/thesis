<?php

declare(strict_types=1);

namespace Thesis\StatementContext\ValueResolver;

use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertSame;

/**
 * @internal
 * @covers \Thesis\StatementContext\ValueResolver\LikeEscaper
 * @group unit
 */
final class LikeEscaperTest extends TestCase
{
    /**
     * @dataProvider escapeProvider
     */
    public function testEscape(string $escapeCharacter, string $value, string $expectedValue): void
    {
        $likeEscaper = new LikeEscaper($escapeCharacter);

        $result = $likeEscaper->escape($value);

        assertSame($expectedValue, $result);
    }

    /**
     * @return \Generator<int, array{string, string, string}>
     */
    public function escapeProvider(): \Generator
    {
        yield ['/', 'a', 'a'];
        yield ['/', '%', '/%'];
        yield ['/', '_', '/_'];
        yield ['/', '/', '//'];
        yield ['!', 'a', 'a'];
        yield ['!', '%', '!%'];
        yield ['!', '_', '!_'];
        yield ['!', '!', '!!'];
    }
}
