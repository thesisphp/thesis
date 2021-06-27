<?php

declare(strict_types=1);

namespace Thesis\StatementContext\ValueResolverRegistry;

use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Thesis\StatementContext\ValueResolver;
use Thesis\StatementContext\ValueResolverRegistry;

final class ContainerValueResolverRegistry implements ValueResolverRegistry
{
    /**
     * @param ContainerInterface<string, ValueResolver> $valueResolvers
     */
    public function __construct(
        private ContainerInterface $valueResolvers,
    ) {
    }

    public function get(string $valueType): ?ValueResolver
    {
        try {
            return $this->valueResolvers->get($valueType);
        } catch (NotFoundExceptionInterface) {
            return null;
        }
    }
}
