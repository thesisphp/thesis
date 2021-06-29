<?php

declare(strict_types=1);

namespace Thesis\StatementContext\ValueResolver;

final class Json
{
    public const NEVER = 0;
    public const ROOT = 1;
    public const ALWAYS = 2;

    /**
     * @param self::* $forceObject
     */
    public function __construct(
        public mixed $value,
        public int $forceObject = self::NEVER,
    ) {
    }
}
