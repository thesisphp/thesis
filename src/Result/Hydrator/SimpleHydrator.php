<?php

declare(strict_types=1);

namespace Thesis\Result\Hydrator;

use Thesis\Result\Hydrator;

final class SimpleHydrator implements Hydrator
{
    /**
     * @psalm-suppress MixedMethodCall
     */
    public function hydrate(mixed $data, string $class): object
    {
        if (\is_array($data)) {
            return new $class(...array_values($data));
        }

        return new $class($data);
    }
}
