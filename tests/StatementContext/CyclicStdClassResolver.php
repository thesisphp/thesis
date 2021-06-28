<?php

declare(strict_types=1);

namespace Thesis\StatementContext;

/**
 * @implements ValueResolver<\stdClass>
 */
final class CyclicStdClassResolver implements ValueResolver
{
    public static function valueTypes(): array
    {
        return [\stdClass::class];
    }

    public function resolve(mixed $value, ValueRecursiveResolver $resolver): string
    {
        return $resolver->resolve(new \stdClass());
    }
}
