<?php

declare(strict_types=1);

namespace Thesis\StatementExecutor;

/**
 * @psalm-immutable
 */
final class PdoValue
{
    /**
     * @psalm-param \PDO::PARAM_* $type
     */
    public function __construct(
        public null|bool|int|string $value,
        public int $type,
    ) {
    }

    /**
     * @psalm-pure
     */
    public static function fromMixed(mixed $value): self
    {
        return match (get_debug_type($value)) {
            self::class => $value,
            'null' => new self(null, \PDO::PARAM_NULL),
            'bool' => new self($value, \PDO::PARAM_BOOL),
            'int' => new self($value, \PDO::PARAM_INT),
            'float', 'string', \Stringable::class => new self((string) $value, \PDO::PARAM_STR),
            default => throw new \InvalidArgumentException(sprintf(
                'Value of type "%s" is not supported by PdoStatementExecutor.',
                get_debug_type($value),
            )),
        };
    }
}
