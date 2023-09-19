<?php

namespace Tests\Infra\Listeners;

use App\Internal\Events\BalanceUpdatedEventInterface;
use Tests\Infra\Exceptions\UnknownEventException;
use DateTimeInterface;

final class BalanceUpdatedThrowDateListener
{
    public function handle(BalanceUpdatedEventInterface $balanceChangedEvent): never
    {
        throw new UnknownEventException(
            $balanceChangedEvent->getUpdatedAt()
                ->format(DateTimeInterface::ATOM),
            (int) $balanceChangedEvent->getBalance()
        );
    }
}
