<?php

declare(strict_types=1);

namespace Thesis\StatementContext\ValueResolver;

final class LikeEscaper
{
    private string $escapePattern;
    private string $escapeReplacement;

    public function __construct(
        string $escapeCharacter = '/',
    ) {
        $this->escapePattern = '~([%_'.preg_quote($escapeCharacter, '~').'])~';
        $this->escapeReplacement = $escapeCharacter.'$0';
    }

    public function escape(string $value): string
    {
        return preg_replace($this->escapePattern, $this->escapeReplacement, $value);
    }
}
