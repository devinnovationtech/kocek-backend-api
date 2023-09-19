<?php

namespace App\External\Api;

use App\External\Contracts\ExtraDtoInterface;
use App\Interfaces\Wallet;

final class TransferQuery
{
    /**
     * @param array<mixed>|ExtraDtoInterface|null $meta
     */
    public function __construct(
        private readonly Wallet $from,
        private readonly Wallet $to,
        private readonly float|int|string $amount,
        private readonly array|ExtraDtoInterface|null $meta
    ) {
    }

    public function getFrom(): Wallet
    {
        return $this->from;
    }

    public function getTo(): Wallet
    {
        return $this->to;
    }

    public function getAmount(): float|int|string
    {
        return $this->amount;
    }

    /**
     * @return array<mixed>|ExtraDtoInterface|null
     */
    public function getMeta(): array|ExtraDtoInterface|null
    {
        return $this->meta;
    }
}
