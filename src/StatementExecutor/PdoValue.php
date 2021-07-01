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
            'null' => new self(null, \PDO::PARAM_NULL),
            'bool' => new self($value, \PDO::PARAM_BOOL),
            'int' => new self($value, \PDO::PARAM_INT),
            'float', 'string' => new self((string) $value, \PDO::PARAM_STR),
            self::class => $value,
            default => throw new \InvalidArgumentException(sprintf(
                'PdoStatementExecutor expects value of type null|scalar|PdoValue, %s given.',
                get_debug_type($value),
            )),
        };
    }
}
