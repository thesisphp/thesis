<?php

declare(strict_types=1);

namespace Thesis\Result;

/**
 * @psalm-immutable
 */
final class DTO
{
    public function __construct(
        public string $property1,
        public string $property2 = '',
    ) {
    }
}
