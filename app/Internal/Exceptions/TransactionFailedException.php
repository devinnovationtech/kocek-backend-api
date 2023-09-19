<?php

namespace App\Internal\Exceptions;

use LogicException;

final class TransactionFailedException extends LogicException implements LogicExceptionInterface
{
}
