<?php

namespace App\Exceptions;

use App\Internal\Exceptions\LogicExceptionInterface;
use LogicException;

final class InsufficientFunds extends LogicException implements LogicExceptionInterface
{
}
