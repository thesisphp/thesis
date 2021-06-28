<?php

declare(strict_types=1);

namespace Thesis\Result\Hydrator;

use PHPUnit\Framework\TestCase;
use Thesis\Result\DTO;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertSame;

/**
 * @internal
 * @covers \Thesis\Result\Hydrator\SimpleInstantiatingHydrator
 * @group unit
 */
final class SimpleInstantiatingHydratorTest extends TestCase
{
    public function testHydrate(): void
    {
        $hydrator = new SimpleInstantiatingHydrator();

        $result = $hydrator->hydrate('test', DTO::class);

        assertInstanceOf(DTO::class, $result);
        assertSame('test', $result->property1);
    }

    public function testHydrateFromArray(): void
    {
        $hydrator = new SimpleInstantiatingHydrator();

        $result = $hydrator->hydrate(['test1', 'test2'], DTO::class);

        assertInstanceOf(DTO::class, $result);
        assertSame('test1', $result->property1);
        assertSame('test2', $result->property2);
    }
}
