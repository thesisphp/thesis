<?php

declare(strict_types=1);

namespace Thesis\StatementContext;

/**
 * @internal
 * @psalm-internal Thesis\StatementContext
 */
final class Parameters
{
    /**
     * @var array<string, mixed>
     * @psalm-readonly-allow-private-mutation
     */
    public array $parameters = [];
    private int $unnamedParametersCount = 0;

    public function add(?string $name, mixed $value): string
    {
        $name = $this->resolveName($name);
        $this->parameters[$name] = $value;

        return $name;
    }

    private function resolveName(?string $name): string
    {
        if ($name === null) {
            return 'p'.($this->unnamedParametersCount++);
        }

        if (!preg_match('/^\w+$/', $name)) {
            throw new \InvalidArgumentException(sprintf('Invalid parameter name %s.', $name));
        }

        return $name;
    }
}
