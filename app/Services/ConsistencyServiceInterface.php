<?php

namespace App\Services;

use App\Exceptions\AmountInvalid;
use App\Exceptions\BalanceIsEmpty;
use App\Exceptions\InsufficientFunds;
use App\Interfaces\Wallet;
use App\Internal\Dto\TransferLazyDtoInterface;

/**
 * @api
 */
interface ConsistencyServiceInterface
{
    /**
     * @throws AmountInvalid
     */
    public function checkPositive(float|int|string $amount): void;

    /**
     * @throws BalanceIsEmpty
     * @throws InsufficientFunds
     */
    public function checkPotential(Wallet $object, float|int|string $amount, bool $allowZero = false): void;

    public function canWithdraw(float|int|string $balance, float|int|string $amount, bool $allowZero = false): bool;

    /**
     * @param TransferLazyDtoInterface[] $objects
     *
     * @throws BalanceIsEmpty
     * @throws InsufficientFunds
     */
    public function checkTransfer(array $objects): void;
}
