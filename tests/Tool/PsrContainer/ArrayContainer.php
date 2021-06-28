<?php

declare(strict_types=1);

namespace Thesis\Tool\PsrContainer;

use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * @template TId of string
 * @template TItem
 * @implements ContainerInterface<TId, TItem>
 */
final class ArrayContainer implements ContainerInterface
{
    /**
     * @param array<TId, TItem> $items
     */
    public function __construct(
        private array $items = [],
    ) {
    }

    public function get($id): mixed
    {
        if (!\array_key_exists($id, $this->items)) {
            throw new class(sprintf('Service "%s" not found in container.', $id)) extends \Exception implements NotFoundExceptionInterface {};
        }

        return $this->items[$id];
    }

    public function has($id): bool
    {
        return \array_key_exists($id, $this->items);
    }
}
