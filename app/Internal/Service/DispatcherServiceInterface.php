<?php

namespace App\Internal\Service;

use App\Internal\Events\EventInterface;

interface DispatcherServiceInterface
{
    public function dispatch(EventInterface $event): void;

    public function forgot(): void;

    public function flush(): void;

    public function lazyFlush(): void;
}
