<?php

declare(strict_types=1);

namespace Thesis\Result;

interface Hydrator
{
    /**
     * @template T of object
     * @param class-string<T> $class
     * @return T
     */
    public function hydrate(mixed $data, string $class): object;
}
