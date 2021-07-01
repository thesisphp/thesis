<?php

declare(strict_types=1);

namespace Thesis\StatementExecutor\Exception;

use Thesis\StatementExecutor\StatementExecutionException;

final class UniqueConstraintViolationException extends StatementExecutionException
{
}
