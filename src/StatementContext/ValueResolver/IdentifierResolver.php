<?php

declare(strict_types=1);

namespace Thesis\StatementContext\ValueResolver;

use Thesis\StatementContext\ValueRecursiveResolver;
use Thesis\StatementContext\ValueResolver;

/**
 * @implements ValueResolver<Identifier>
 */
final class IdentifierResolver implements ValueResolver
{
    private const DEFAULT_QUOTE_CHARACTER = '"';
    private const DEFAULT_DELIMITER = '.';

    public function __construct(
        private string $quoteCharacter = self::DEFAULT_QUOTE_CHARACTER,
        private string $delimiter = self::DEFAULT_DELIMITER,
    ) {
    }

    public static function valueTypes(): array
    {
        return [Identifier::class];
    }

    /**
     * @param Identifier $value
     */
    public function resolve(mixed $value, ValueRecursiveResolver $resolver): string
    {
        return implode(
            $this->delimiter,
            array_map(
                [$this, 'quote'],
                explode($this->delimiter, $value->identifier),
            ),
        );
    }

    private function quote(string $identifier): string
    {
        $char = $this->quoteCharacter;

        return $char.str_replace($char, $char.$char, $identifier).$char;
    }
}
