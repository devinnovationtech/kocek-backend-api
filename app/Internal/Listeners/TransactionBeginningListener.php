<?php

namespace App\Internal\Listeners;

use App\Internal\Service\ConnectionServiceInterface;
use App\Services\RegulatorServiceInterface;

final class TransactionBeginningListener
{
    public function __invoke(): void
    {
        if (app(ConnectionServiceInterface::class)->get()->transactionLevel() === 1) {
            app(RegulatorServiceInterface::class)->purge();
        }
    }
}
