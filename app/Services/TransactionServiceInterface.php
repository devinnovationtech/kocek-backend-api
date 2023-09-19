<?php

namespace App\Services;

use App\Interfaces\Wallet;
use App\Internal\Dto\TransactionDtoInterface;
use App\Internal\Exceptions\RecordNotFoundException;
use App\Models\Transaction;

/**
 * @api
 */
interface TransactionServiceInterface
{
    /**
     * @param null|array<mixed> $meta
     *
     * @throws RecordNotFoundException
     */
    public function makeOne(
        Wallet $wallet,
        string $type,
        float|int|string $amount,
        ?array $meta,
        bool $confirmed = true
    ): Transaction;

    /**
     * @param non-empty-array<int, Wallet> $wallets
     * @param non-empty-array<int, TransactionDtoInterface> $objects
     *
     * @throws RecordNotFoundException
     *
     * @return non-empty-array<string, Transaction>
     */
    public function apply(array $wallets, array $objects): array;
}
