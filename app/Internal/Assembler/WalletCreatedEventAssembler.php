<?php

namespace App\Internal\Assembler;

use App\Internal\Events\WalletCreatedEvent;
use App\Internal\Events\WalletCreatedEventInterface;
use App\Internal\Service\ClockServiceInterface;
use App\Models\Wallet;

final class WalletCreatedEventAssembler implements WalletCreatedEventAssemblerInterface
{
    public function __construct(
        private readonly ClockServiceInterface $clockService
    ) {
    }

    public function create(Wallet $wallet): WalletCreatedEventInterface
    {
        return new WalletCreatedEvent(
            $wallet->holder_type,
            $wallet->holder_id,
            $wallet->uuid,
            $wallet->getKey(),
            $this->clockService->now()
        );
    }
}
