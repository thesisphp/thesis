<?php

declare(strict_types=1);

namespace Thesis\StatementContext;

/**
 * @implements ValueResolver<\Stringable>
 */
final class StringableResolver implements ValueResolver
{
    public static function valueTypes(): array
    {
        return [\Stringable::class];
    }

    public function resolve(mixed $value, ValueRecursiveResolver $resolver): string
    {
        return $resolver->resolve((string) $value);
    }
}
