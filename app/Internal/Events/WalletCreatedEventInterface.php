<?php

namespace App\Internal\Events;

use DateTimeImmutable;

interface WalletCreatedEventInterface extends EventInterface
{
    public function getHolderType(): string;

    public function getHolderId(): int|string;

    public function getWalletId(): int;

    public function getWalletUuid(): string;

    public function getCreatedAt(): DateTimeImmutable;
}
