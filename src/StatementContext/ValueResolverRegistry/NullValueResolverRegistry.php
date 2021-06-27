<?php

declare(strict_types=1);

namespace Thesis\StatementContext\ValueResolverRegistry;

use Thesis\StatementContext\ValueResolver;
use Thesis\StatementContext\ValueResolverRegistry;

final class NullValueResolverRegistry implements ValueResolverRegistry
{
    public function get(string $valueType): ?ValueResolver
    {
        return null;
    }
}
