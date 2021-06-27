<?php

declare(strict_types=1);

namespace Thesis\Result;

interface ColumnType
{
    /**
     * @throws \UnexpectedValueException
     */
    public function transform(mixed $value): mixed;
}
