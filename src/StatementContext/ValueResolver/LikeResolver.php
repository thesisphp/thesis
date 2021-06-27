<?php

declare(strict_types=1);

namespace Thesis\StatementContext\ValueResolver;

use Thesis\StatementContext\ValueRecursiveResolver;
use Thesis\StatementContext\ValueResolver;

/**
 * @implements ValueResolver<Like>
 */
final class LikeResolver implements ValueResolver
{
    private const DEFAULT_ESCAPE_CHARACTER = '/';

    private LikeEscaper $escaper;
    private string $escapeExpression;

    public function __construct(
        string $escapeCharacter = self::DEFAULT_ESCAPE_CHARACTER,
    ) {
        $this->escaper = new LikeEscaper($escapeCharacter);
        $this->escapeExpression = " escape '{$escapeCharacter}'";
    }

    public static function valueTypes(): array
    {
        return [Like::class];
    }

    /**
     * @param Like $value
     */
    public function resolve(mixed $value, ValueRecursiveResolver $resolver): string
    {
        return $resolver->resolve(($value->query)($this->escaper)).$this->escapeExpression;
    }
}
