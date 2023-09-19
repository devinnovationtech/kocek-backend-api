<?php

namespace App\Internal\Repository;

use App\Internal\Dto\TransferDtoInterface;
use App\Internal\Query\TransferQueryInterface;
use App\Models\Transfer;

interface TransferRepositoryInterface
{
    /**
     * @param non-empty-array<int|string, TransferDtoInterface> $objects
     */
    public function insert(array $objects): void;

    public function insertOne(TransferDtoInterface $dto): Transfer;

    /**
     * @return Transfer[]
     */
    public function findBy(TransferQueryInterface $query): array;

    /**
     * @param non-empty-array<int> $ids
     */
    public function updateStatusByIds(string $status, array $ids): int;
}
