<?php

declare(strict_types=1);

namespace Thesis\StatementContext;

/**
 * @template T
 */
interface ValueResolver
{
    public const TYPE_NULL = 'null';
    public const TYPE_BOOL = 'bool';
    public const TYPE_INT = 'int';
    public const TYPE_FLOAT = 'float';
    public const TYPE_STRING = 'string';
    public const TYPE_ARRAY = 'array';

    /**
     * @return non-empty-list<class-string|self::TYPE_*>
     */
    public static function valueTypes(): array;

    /**
     * @param T $value
     */
    public function resolve(mixed $value, ValueRecursiveResolver $resolver): string;
}
