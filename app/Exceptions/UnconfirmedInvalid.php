<?php

namespace App\Exceptions;

use App\Internal\Exceptions\InvalidArgumentExceptionInterface;
use InvalidArgumentException;

final class UnconfirmedInvalid extends InvalidArgumentException implements InvalidArgumentExceptionInterface
{
}
