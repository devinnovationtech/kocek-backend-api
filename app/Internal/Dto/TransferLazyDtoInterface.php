<?php

namespace App\Internal\Dto;

use App\Interfaces\Wallet;

interface TransferLazyDtoInterface
{
    public function getFromWallet(): Wallet;

    public function getToWallet(): Wallet;

    public function getDiscount(): int;

    public function getFee(): string;

    public function getWithdrawDto(): TransactionDtoInterface;

    public function getDepositDto(): TransactionDtoInterface;

    public function getStatus(): string;

    public function getUuid(): ?string;
}
