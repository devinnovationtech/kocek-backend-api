<?php

namespace App\Internal\Assembler;

use App\Interfaces\Wallet;
use App\Internal\Dto\TransactionDtoInterface;
use App\Internal\Dto\TransferLazyDto;
use App\Internal\Dto\TransferLazyDtoInterface;

final class TransferLazyDtoAssembler implements TransferLazyDtoAssemblerInterface
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
    ): TransferLazyDtoInterface {
        return new TransferLazyDto($fromWallet, $toWallet, $discount, $fee, $withdrawDto, $depositDto, $status, $uuid);
    }
}
