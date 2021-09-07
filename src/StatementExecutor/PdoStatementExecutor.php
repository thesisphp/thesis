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

    public function execute(string $statement, array $parameters = []): ExecutedStatement
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

        ob_start();
        $pdoStatement->debugDumpParams();
        $debugInfo = ['pdo' => ob_get_clean() ?: ''];

        return new ExecutedStatement($pdoStatement, $pdoStatement->rowCount(), $debugInfo);
    }
}
