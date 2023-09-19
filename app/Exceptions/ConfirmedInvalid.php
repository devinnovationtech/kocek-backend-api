<?php

namespace App\Exceptions;

use App\Internal\Exceptions\InvalidArgumentExceptionInterface;
use InvalidArgumentException;

final class ConfirmedInvalid extends InvalidArgumentException implements InvalidArgumentExceptionInterface
{
}
