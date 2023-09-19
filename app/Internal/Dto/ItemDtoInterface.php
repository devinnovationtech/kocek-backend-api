<?php

namespace App\Internal\Dto;

use App\Interfaces\ProductInterface;
use App\Interfaces\Wallet;
use Countable;

interface ItemDtoInterface extends Countable
{
    /**
     * @return ProductInterface[]
     */
    public function getItems(): array;

    public function getPricePerItem(): int|string|null;

    public function getProduct(): ProductInterface;

    public function getReceiving(): ?Wallet;
}
