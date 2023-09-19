<?php

namespace App\Internal\Assembler;

use App\Internal\Events\TransactionCreatedEvent;
use App\Internal\Events\TransactionCreatedEventInterface;
use App\Internal\Service\ClockServiceInterface;
use App\Models\Transaction;

final class TransactionCreatedEventAssembler implements TransactionCreatedEventAssemblerInterface
{
    public function __construct(
        private readonly ClockServiceInterface $clockService
    ) {
    }

    public function create(Transaction $transaction): TransactionCreatedEventInterface
    {
        return new TransactionCreatedEvent(
            $transaction->getKey(),
            $transaction->type,
            $transaction->wallet_id,
            $this->clockService->now(),
        );
    }
}
