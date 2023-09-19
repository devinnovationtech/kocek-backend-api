<?php

namespace App\Internal\Listeners;

use App\Internal\Service\ConnectionServiceInterface;
use App\Services\RegulatorServiceInterface;

final class TransactionCommittedListener
{
    public function __invoke(): void
    {
        if (app(ConnectionServiceInterface::class)->get()->transactionLevel() === 0) {
            app(RegulatorServiceInterface::class)->committed();
        }
    }
}
