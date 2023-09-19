<?php

namespace App\Internal\Exceptions;

use InvalidArgumentException;

final class CartEmptyException extends InvalidArgumentException implements InvalidArgumentExceptionInterface
{
}
