<?php

namespace App\Internal\Repository;

use App\Internal\Dto\TransactionDtoInterface;
use App\Internal\Query\TransactionQueryInterface;
use App\Models\Transaction;

interface TransactionRepositoryInterface
{
    /**
     * @param non-empty-array<int|string, TransactionDtoInterface> $objects
     */
    public function insert(array $objects): void;

    public function insertOne(TransactionDtoInterface $dto): Transaction;

    /**
     * @return Transaction[]
     */
    public function findBy(TransactionQueryInterface $query): array;
}
