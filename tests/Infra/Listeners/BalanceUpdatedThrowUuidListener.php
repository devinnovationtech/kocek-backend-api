<?php

namespace Tests\Infra\Listeners;

use App\Internal\Events\BalanceUpdatedEventInterface;
use Tests\Infra\Exceptions\UnknownEventException;

final class BalanceUpdatedThrowUuidListener
{
    public function handle(BalanceUpdatedEventInterface $balanceChangedEvent): never
    {
        throw new UnknownEventException(
            $balanceChangedEvent->getWalletUuid(),
            ((int) $balanceChangedEvent->getBalance()) + $balanceChangedEvent->getWalletId(),
        );
    }
}
