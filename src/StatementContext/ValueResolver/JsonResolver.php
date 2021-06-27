<?php

declare(strict_types=1);

namespace Thesis\StatementContext\ValueResolver;

use Thesis\StatementContext\ValueRecursiveResolver;
use Thesis\StatementContext\ValueResolver;

/**
 * @implements ValueResolver<Json>
 */
final class JsonResolver implements ValueResolver
{
    public static function valueTypes(): array
    {
        return [Json::class];
    }

    /**
     * @param Json $value
     * @throws \JsonException
     */
    public function resolve(mixed $value, ValueRecursiveResolver $resolver): string
    {
        if ($value->forceObject === Json::ROOT && $value->value === []) {
            return $resolver->resolve('{}');
        }

        $options = JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR;

        if ($value->forceObject === Json::ALWAYS) {
            $options = $options | JSON_FORCE_OBJECT;
        }

        return $resolver->resolve(json_encode($value->value, $options));
    }
}
