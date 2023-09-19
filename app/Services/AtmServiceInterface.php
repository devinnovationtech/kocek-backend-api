<?php

namespace App\Services;

use App\Internal\Dto\TransactionDtoInterface;
use App\Internal\Dto\TransferDtoInterface;
use App\Models\Transaction;
use App\Models\Transfer;

/**
 * @api
 */
interface AtmServiceInterface
{
    /**
     * Helps to get to create a bunch of transaction objects.
     *
     * @param non-empty-array<array-key, TransactionDtoInterface> $objects
     *
     * @return non-empty-array<string, Transaction>
     */
    public function makeTransactions(array $objects): array;

    /**
     * Helps to get to create a bunch of transfer objects.
     *
     * @param non-empty-array<array-key, TransferDtoInterface> $objects
     *
     * @return non-empty-array<string, Transfer>
     */
    public function makeTransfers(array $objects): array;
}
