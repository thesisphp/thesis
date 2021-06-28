<?php

declare(strict_types=1);

namespace Thesis\StatementContext;

/**
 * @implements ValueResolver<\Exception>
 */
final class ExceptionResolver implements ValueResolver
{
    public static function valueTypes(): array
    {
        return [\Exception::class];
    }

    public function resolve(mixed $value, ValueRecursiveResolver $resolver): string
    {
        return $resolver->resolve((string) $value);
    }
}
