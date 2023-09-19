<?php

namespace App\Internal\Assembler;

use App\Interfaces\Wallet;
use App\Internal\Dto\TransactionDtoInterface;
use App\Internal\Dto\TransferLazyDtoInterface;

interface TransferLazyDtoAssemblerInterface
{
    public function create(
        Wallet $fromWallet,
        Wallet $toWallet,
        int $discount,
        string $fee,
        TransactionDtoInterface $withdrawDto,
        TransactionDtoInterface $depositDto,
        string $status,
        ?string $uuid
    ): TransferLazyDtoInterface;
}
