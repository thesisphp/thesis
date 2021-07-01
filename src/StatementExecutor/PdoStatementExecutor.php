<?php

declare(strict_types=1);

namespace Thesis\StatementExecutor;

use Thesis\StatementExecutor\Exception\UnresolvedException;

final class PdoStatementExecutor implements StatementExecutor
{
    public function __construct(
        private \PDO $pdo,
    ) {
    }

    public function execute(string $statement, array $parameters = [], bool $debug = false): ExecutedStatement
    {
        /** @var \PDOStatement<array> $pdoStatement */
        $pdoStatement = $this->pdo->prepare($statement);

        foreach ($parameters as $name => $value) {
            $pdoValue = PdoValue::fromMixed($value);
            $pdoStatement->bindValue($name, $pdoValue->value, $pdoValue->type);
        }

        try {
            $pdoStatement->execute();
        } catch (\PDOException $exception) {
            throw new UnresolvedException($exception->getMessage(), (string) $exception->getCode(), $exception);
        }

        $pdoStatement->setFetchMode(\PDO::FETCH_ASSOC);

        $debugMessage = '';

        if ($debug) {
            ob_start();
            $pdoStatement->debugDumpParams();
            $debugMessage = ob_get_clean() ?: '';
        }

        return new ExecutedStatement($pdoStatement, $pdoStatement->rowCount(), $debugMessage);
    }
}
