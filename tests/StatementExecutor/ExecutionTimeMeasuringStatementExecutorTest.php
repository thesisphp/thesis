<?php

declare(strict_types=1);

namespace Thesis\StatementExecutor;

use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertSame;

/**
 * @internal
 * @covers \Thesis\StatementExecutor\ExecutionTimeMeasuringStatementExecutor
 * @group unit
 */
final class ExecutionTimeMeasuringStatementExecutorTest extends TestCase
{
    public function testAddsTime(): void
    {
        $executor = $this->createMock(StatementExecutor::class);
        $executor->method('execute')->willReturn(new ExecutedStatement(new \ArrayIterator(), 0));
        $timeDebuggingExecutor = new ExecutionTimeMeasuringStatementExecutor($executor, static function (): int {
            /** @var int */
            static $time = 0;

            return $time++;
        });

        $executedStatement = $timeDebuggingExecutor->execute('');

        assertSame(['execution_time_ms' => 1000], $executedStatement->debugData);
    }
}
