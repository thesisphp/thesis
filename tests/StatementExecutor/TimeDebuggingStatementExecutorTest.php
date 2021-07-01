<?php

declare(strict_types=1);

namespace Thesis\StatementExecutor;

use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertSame;

/**
 * @internal
 * @covers \Thesis\StatementExecutor\TimeDebuggingStatementExecutor
 * @group unit
 */
final class TimeDebuggingStatementExecutorTest extends TestCase
{
    public function testDoesNotAddTimeInNonDebugMode(): void
    {
        $executor = $this->createMock(StatementExecutor::class);
        $executor->method('execute')->willReturn(new ExecutedStatement(new \ArrayIterator(), 0));
        $timeDebuggingExecutor = new TimeDebuggingStatementExecutor($executor);

        $executedStatement = $timeDebuggingExecutor->execute('');

        assertSame([], $executedStatement->debugContext);
    }

    public function testAddsTimeInDebugMode(): void
    {
        $executor = $this->createMock(StatementExecutor::class);
        $executor->method('execute')->willReturn(new ExecutedStatement(new \ArrayIterator(), 0));
        $timeDebuggingExecutor = new TimeDebuggingStatementExecutor($executor, static function (): int {
            /** @var int */
            static $time = 0;

            return $time++;
        });

        $executedStatement = $timeDebuggingExecutor->execute('', debug: true);

        assertSame(['time_ms' => 1000], $executedStatement->debugContext);
    }
}
