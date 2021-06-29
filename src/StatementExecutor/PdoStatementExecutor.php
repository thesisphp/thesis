<?php

declare(strict_types=1);

namespace Thesis\StatementExecutor;

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

        $pdoStatement->execute();
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
