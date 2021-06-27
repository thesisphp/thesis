<?php

declare(strict_types=1);

namespace Thesis\StatementContext;

interface ValueResolverRegistry
{
    public function get(string $valueType): ?ValueResolver;
}
