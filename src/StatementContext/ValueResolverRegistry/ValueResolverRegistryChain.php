<?php

declare(strict_types=1);

namespace Thesis\StatementContext\ValueResolverRegistry;

use Thesis\StatementContext\ValueResolver;
use Thesis\StatementContext\ValueResolverRegistry;

final class ValueResolverRegistryChain implements ValueResolverRegistry
{
    /**
     * @param iterable<ValueResolverRegistry> $valueResolverRegistries
     */
    public function __construct(
        private iterable $valueResolverRegistries,
    ) {
    }

    public function get(string $valueType): ?ValueResolver
    {
        foreach ($this->valueResolverRegistries as $valueResolverRegistry) {
            $valueResolver = $valueResolverRegistry->get($valueType);

            if ($valueResolver !== null) {
                return $valueResolver;
            }
        }

        return null;
    }
}
