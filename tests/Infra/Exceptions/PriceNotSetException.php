<?php

namespace Tests\Infra\Exceptions;

use App\Internal\Exceptions\InvalidArgumentExceptionInterface;
use InvalidArgumentException;

final class PriceNotSetException extends InvalidArgumentException implements InvalidArgumentExceptionInterface
{
}
