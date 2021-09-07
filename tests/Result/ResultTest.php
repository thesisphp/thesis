<?php

declare(strict_types=1);

namespace Thesis\Result;

use PHPUnit\Framework\TestCase;
use Thesis\Result\Hydrator\SimpleInstantiatingHydrator;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertSame;

/**
 * @internal
 * @covers \Thesis\Result\Result
 * @group unit
 */
final class ResultTest extends TestCase
{
    public function testMapKey(): void
    {
        $thesisResult = Result::create(new \ArrayIterator(['key' => ['test' => '123.12']]), 1);

        $result = $thesisResult->mapKey(static fn () => 'new_key');

        assertSame(['new_key' => ['test' => '123.12']], $result->toArray());
    }

    public function testMapRow(): void
    {
        $thesisResult = Result::create(new \ArrayIterator(['key' => 'row']), 1);

        $result = $thesisResult->mapRow(static fn () => 'new row');

        assertSame(['key' => 'new row'], $result->toArray());
    }

    public function testKeyColumn(): void
    {
        $thesisResult = Result::create(
            new \ArrayIterator([
                ['test_id' => '333b3fe1-c425-4c6c-b07e-827dad427ce2', 'test' => 'test1'],
                ['test_id' => '679d177f-92a2-45ba-a8a2-7d1e92e29c6e', 'test' => 'test2'],
            ]),
            1,
        );

        $result = $thesisResult->keyColumn('test_id');

        assertSame(
            [
                '333b3fe1-c425-4c6c-b07e-827dad427ce2' => ['test_id' => '333b3fe1-c425-4c6c-b07e-827dad427ce2', 'test' => 'test1'],
                '679d177f-92a2-45ba-a8a2-7d1e92e29c6e' => ['test_id' => '679d177f-92a2-45ba-a8a2-7d1e92e29c6e', 'test' => 'test2'],
            ],
            $result->toArray(),
        );
    }

    public function testKeyColumnRaisesExceptionWhenResultRowIsNotArray(): void
    {
        $thesisResult = Result::create(new \ArrayIterator(['test']), 1);

        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage("Result::keyColumn('test') expects row value to be of type array{test: mixed}, got string.");

        $this->traverse($thesisResult->keyColumn('test'));
    }

    public function testKeyColumnRaisesExceptionWhenKeyNotExistInArray(): void
    {
        $thesisResult = Result::create(new \ArrayIterator([['test' => 'test']]), 1);

        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage("Result::keyColumn('test2') expects row array to have offset 'test2', got array with offsets 'test'.");

        $this->traverse($thesisResult->keyColumn('test2'));
    }

    public function testRowColumn(): void
    {
        $thesisResult = Result::create(
            new \ArrayIterator([
                '333b3fe1-c425-4c6c-b07e-827dad427ce2' => ['test' => 'test1'],
                '679d177f-92a2-45ba-a8a2-7d1e92e29c6e' => ['test' => 'test2'],
            ]),
            1,
        );

        $result = $thesisResult->rowColumn('test');

        assertSame(
            [
                '333b3fe1-c425-4c6c-b07e-827dad427ce2' => 'test1',
                '679d177f-92a2-45ba-a8a2-7d1e92e29c6e' => 'test2',
            ],
            $result->toArray(),
        );
    }

    public function testRowColumnRaisesExceptionWhenResultRowIsNotArray(): void
    {
        $thesisResult = Result::create(new \ArrayIterator(['test']), 1);

        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage("Result::rowColumn('test') expects row value to be of type array{test: mixed}, got string.");

        $this->traverse($thesisResult->rowColumn('test'));
    }

    public function testRowColumnRaisesExceptionWhenKeyNotExistInArray(): void
    {
        $thesisResult = Result::create(new \ArrayIterator([['test' => 'test']]), 1);

        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage("Result::rowColumn('test2') expects row array to have offset 'test2', got array with offsets 'test'.");

        $this->traverse($thesisResult->rowColumn('test2'));
    }

    public function testHydrate(): void
    {
        $thesisResult = Result::create(
            new \ArrayIterator(['key' => ['test1_key' => 'test1', 'test2_key' => 'test2']]),
            1,
            new SimpleInstantiatingHydrator(),
        );

        $result = $thesisResult->hydrate(DTO::class);

        assertEquals(['key' => new DTO('test1', 'test2')], $result->toArray());
    }

    public function testToArray(): void
    {
        $thesisResult = Result::create(new \ArrayIterator(['test' => 'test']), 1);

        $result = $thesisResult->toArray();

        assertSame(['test' => 'test'], $result);
    }

    public function testToList(): void
    {
        $thesisResult = Result::create(new \ArrayIterator(['test_key' => 'test']), 1);

        $result = $thesisResult->toList();

        assertSame(['test'], $result);
    }

    public function testFetch(): void
    {
        $thesisResult = Result::create(new \ArrayIterator([['test1' => 'test1'], ['test2' => 'test2']]), 1);

        $result1 = $thesisResult->fetch(static fn () => 'default');
        $result2 = $thesisResult->fetch(static fn () => 'default');
        $result3 = $thesisResult->fetch(static fn () => 'default');

        assertSame(['test1' => 'test1'], $result1);
        assertSame(['test2' => 'test2'], $result2);
        assertSame('default', $result3);
    }

    private function traverse(iterable $iterable): void
    {
        foreach ($iterable as $_value);
    }
}
