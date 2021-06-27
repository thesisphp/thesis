<?php

declare(strict_types=1);

namespace Thesis\StatementContext\ValueResolver;

/**
 * @psalm-immutable
 */
final class Like
{
    /**
     * @param callable(LikeEscaper): string $query
     */
    public function __construct(
        public $query,
    ) {
    }

    /**
     * @psalm-pure
     */
    public static function contains(string $value): self
    {
        return new self(static fn (LikeEscaper $like): string => "%{$like->escape($value)}%");
    }

    /**
     * @psalm-pure
     */
    public static function startsWith(string $value): self
    {
        return new self(static fn (LikeEscaper $like): string => "{$like->escape($value)}%");
    }

    /**
     * @psalm-pure
     */
    public static function endsWith(string $value): self
    {
        return new self(static fn (LikeEscaper $like): string => "%{$like->escape($value)}");
    }
}
