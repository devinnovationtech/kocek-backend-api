<?php

namespace App\Internal\Events;

use DateTimeImmutable;

interface TransactionCreatedEventInterface extends EventInterface
{
    public function getId(): int;

    public function getType(): string;

    public function getWalletId(): int;

    public function getCreatedAt(): DateTimeImmutable;
}
