<?php

declare(strict_types=1);

namespace Thesis\StatementContext;

use Thesis\StatementContext\ValueResolverRegistry\InMemoryValueResolverRegistry;

/**
 * Thesis Statement conteXt.
 *
 * @psalm-type Statement = string|\Generator<string>|callable(self): string|\Generator<string>
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
    public static function resolve(string|\Generator|callable $statement, ?ValueResolverRegistry $valueResolverRegistry = null): array
    {
        $parameters = new Parameters();
        $context = new self($valueResolverRegistry ?? new InMemoryValueResolverRegistry(), $parameters);

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
    public function embed(string|\Generator|callable $statement): string
    {
        if (\is_callable($statement)) {
            $statement = $statement($this);
        }

        if (\is_string($statement)) {
            return $statement;
        }

        return implode(' ', iterator_to_array($statement, false));
    }

    /**
     * Formats an iterable of values as insert value sets.
     *
     * @template TKey
     * @template TValue
     * @param iterable<TKey, TValue> $values
     * @param callable(TValue, TKey, self): string $formatter
     */
    public function sets(iterable $values, callable $formatter): string
    {
        $string = '';
        $first = true;

        foreach ($values as $key => $value) {
            if ($first) {
                $first = false;
            } else {
                $string .= ', ';
            }

            $string .= '('.$formatter($value, $key, $this).')';
        }

        return $string;
    }
}
