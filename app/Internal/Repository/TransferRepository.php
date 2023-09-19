<?php

namespace App\Internal\Repository;

use App\Internal\Dto\TransferDtoInterface;
use App\Internal\Query\TransferQueryInterface;
use App\Internal\Transform\TransferDtoTransformerInterface;
use App\Models\Transfer;

final class TransferRepository implements TransferRepositoryInterface
{
    public function __construct(
        private readonly TransferDtoTransformerInterface $transformer,
        private readonly Transfer $transfer
    ) {
    }

    /**
     * @param non-empty-array<int|string, TransferDtoInterface> $objects
     */
    public function insert(array $objects): void
    {
        $values = array_map(fn (TransferDtoInterface $dto): array => $this->transformer->extract($dto), $objects);
        $this->transfer->newQuery()
            ->insert($values)
        ;
    }

    public function insertOne(TransferDtoInterface $dto): Transfer
    {
        $attributes = $this->transformer->extract($dto);
        $instance = $this->transfer->newInstance($attributes);
        $instance->saveQuietly();

        return $instance;
    }

    /**
     * @return Transfer[]
     */
    public function findBy(TransferQueryInterface $query): array
    {
        return $this->transfer->newQuery()
            ->whereIn('uuid', $query->getUuids())
            ->get()
            ->all()
        ;
    }

    /**
     * @param non-empty-array<int> $ids
     */
    public function updateStatusByIds(string $status, array $ids): int
    {
        $connection = $this->transfer->getConnection();

        return $this->transfer->newQuery()
            ->toBase()
            ->whereIn($this->transfer->getKeyName(), $ids)
            ->update([
                'status_last' => $connection->raw('status'),
                'status' => $status,
            ])
        ;
    }
}
