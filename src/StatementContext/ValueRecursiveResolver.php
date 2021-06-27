<?php

declare(strict_types=1);

namespace Thesis\StatementContext;

final class ValueRecursiveResolver
{
    /**
     * @var array<string, class-string<ValueResolver>>
     */
    private array $resolvedTypes = [];

    public function __construct(
        private ValueResolverRegistry $valueResolverRegistry,
        private Parameters $parameters,
        private ?string $parameterName,
    ) {
    }

    /**
     * @return \Generator<string>
     */
    private static function valueTypes(mixed $value): \Generator
    {
        yield get_debug_type($value);

        if (\is_object($value)) {
            yield from class_parents($value);
            yield from class_implements($value);
        }
    }

    public function resolve(mixed $value): string
    {
        foreach (self::valueTypes($value) as $type) {
            $valueResolver = $this->valueResolverRegistry->get($type);

            if ($valueResolver === null) {
                continue;
            }

            if (isset($this->resolvedTypes[$type])) {
                throw new \LogicException(sprintf(
                    'Type resolution cycle %s > %s (%s) detected.',
                    implode(' > ', array_map(
                        fn (string $type, string $valueResolver): string => "{$type} ({$valueResolver})",
                        array_keys($this->resolvedTypes),
                        $this->resolvedTypes,
                    )),
                    $type,
                    $valueResolver::class,
                ));
            }

            $this->resolvedTypes[$type] = $valueResolver::class;

            return $valueResolver->resolve($value, $this);
        }

        return ':'.$this->parameters->add($this->parameterName, $value);
    }
}
