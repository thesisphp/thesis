<?php

declare(strict_types=1);

namespace Thesis\StatementContext\ValueResolverRegistry;

use Thesis\StatementContext\ValueResolver;
use Thesis\StatementContext\ValueResolverRegistry;

final class InMemoryValueResolverRegistry implements ValueResolverRegistry
{
    /**
     * @var array<string, ValueResolver>
     */
    private array $valueResolvers = [];

    /**
     * @param iterable<ValueResolver> $valueResolvers
     */
    public function __construct(
        iterable $valueResolvers = [],
    ) {
        foreach ($valueResolvers as $valueResolver) {
            foreach ($valueResolver::valueTypes() as $valueType) {
                $this->valueResolvers[$valueType] = $valueResolver;
            }
        }
    }

    public function get(string $valueType): ?ValueResolver
    {
        return $this->valueResolvers[$valueType] ?? null;
    }
}
