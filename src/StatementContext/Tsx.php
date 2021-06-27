<?php

declare(strict_types=1);

namespace Thesis\StatementContext;

use Thesis\StatementContext\ValueResolverRegistry\NullValueResolverRegistry;

/**
 * Thesis Statement Context.
 *
 * @psalm-type Statement = string|iterable<string>|callable(self): string|iterable<string>
 */
final class Tsx
{
    private function __construct(
        private ValueResolverRegistry $valueResolverRegistry,
        private Parameters $parameters,
    ) {
    }

    /**
     * @param Statement $statement
     * @return array{string, array<string, mixed>}
     */
    public static function resolve(string|iterable|callable $statement, ?ValueResolverRegistry $valueResolverRegistry = null): array
    {
        $parameters = new Parameters();
        $context = new self($valueResolverRegistry ?? new NullValueResolverRegistry(), $parameters);

        return [$context->embed($statement), $parameters->parameters];
    }

    public function __invoke(mixed ...$values): string
    {
        $resolved = [];

        foreach ($values as $name => $value) {
            $resolver = new ValueRecursiveResolver(
                $this->valueResolverRegistry,
                $this->parameters,
                \is_string($name) ? $name : null,
            );

            $resolved[] = $resolver->resolve($value);
        }

        return implode(', ', $resolved);
    }

    /**
     * @param Statement $statement
     */
    public function embed(string|iterable|callable $statement): string
    {
        if (\is_callable($statement)) {
            return $this->embed($statement($this));
        }

        if (\is_string($statement)) {
            return $statement;
        }

        $joined = '';

        foreach ($statement as $piece) {
            $joined .= $piece.' ';
        }

        return $joined;
    }
}
