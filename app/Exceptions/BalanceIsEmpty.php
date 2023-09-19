<?php

namespace App\Exceptions;

use App\Internal\Exceptions\LogicExceptionInterface;
use LogicException;

final class BalanceIsEmpty extends LogicException implements LogicExceptionInterface
{
}
