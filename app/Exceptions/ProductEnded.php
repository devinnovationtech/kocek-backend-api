<?php

namespace App\Exceptions;

use App\Internal\Exceptions\LogicExceptionInterface;
use LogicException;

final class ProductEnded extends LogicException implements LogicExceptionInterface
{
}
