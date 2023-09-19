<?php

namespace App\Internal\Service;

use DateTimeImmutable;

/**
 * @see https://github.com/php-fig/fig-standards/blob/master/proposed/clock.md
 */
interface ClockServiceInterface
{
    public function now(): DateTimeImmutable;
}
