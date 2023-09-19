<?php

namespace Tests\Infra\Exceptions;

use App\Internal\Exceptions\RuntimeExceptionInterface;
use RuntimeException;

final class UnknownEventException extends RuntimeException implements RuntimeExceptionInterface
{
}
