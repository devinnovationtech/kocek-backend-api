<?php

namespace App\Internal\Events;

use DateTimeImmutable;

interface BalanceUpdatedEventInterface extends EventInterface
{
    public function getWalletId(): int;

    public function getWalletUuid(): string;

    public function getBalance(): string;

    public function getUpdatedAt(): DateTimeImmutable;
}
