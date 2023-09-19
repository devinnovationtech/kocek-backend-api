<?php

namespace App\Internal\Service;

use DateTimeImmutable;

final class ClockService implements ClockServiceInterface
{
    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable();
    }
}
