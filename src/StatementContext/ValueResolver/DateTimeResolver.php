<?php

declare(strict_types=1);

namespace Thesis\StatementContext\ValueResolver;

use Thesis\StatementContext\ValueRecursiveResolver;
use Thesis\StatementContext\ValueResolver;

/**
 * @implements ValueResolver<\DateTimeInterface>
 */
final class DateTimeResolver implements ValueResolver
{
    public function __construct(
        private string $format = 'Y-m-d H:i:s',
    ) {
    }

    public static function valueTypes(): array
    {
        return [\DateTimeInterface::class];
    }

    /**
     * @param \DateTimeInterface $value
     */
    public function resolve(mixed $value, ValueRecursiveResolver $resolver): string
    {
        return $resolver->resolve($value->format($this->format));
    }
}
