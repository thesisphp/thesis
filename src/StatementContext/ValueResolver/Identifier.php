<?php

declare(strict_types=1);

namespace Thesis\StatementContext\ValueResolver;

/**
 * @psalm-immutable
 */
final class Identifier
{
    public function __construct(
        public string $identifier,
    ) {
    }
}
