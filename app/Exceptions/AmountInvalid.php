<?php

namespace App\Exceptions;

use App\Internal\Exceptions\InvalidArgumentExceptionInterface;
use InvalidArgumentException;

final class AmountInvalid extends InvalidArgumentException implements InvalidArgumentExceptionInterface
{
}
