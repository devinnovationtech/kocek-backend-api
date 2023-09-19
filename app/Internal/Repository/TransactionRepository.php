<?php

namespace App\Internal\Repository;

use App\Internal\Dto\TransactionDtoInterface;
use App\Internal\Query\TransactionQueryInterface;
use App\Internal\Service\JsonServiceInterface;
use App\Internal\Transform\TransactionDtoTransformerInterface;
use App\Models\Transaction;

final class TransactionRepository implements TransactionRepositoryInterface
{
    public function __construct(
        private readonly TransactionDtoTransformerInterface $transformer,
        private readonly JsonServiceInterface $jsonService,
        private readonly Transaction $transaction
    ) {
    }

    /**
     * @param non-empty-array<int|string, TransactionDtoInterface> $objects
     */
    public function insert(array $objects): void
    {
        $values = [];
        foreach ($objects as $object) {
            $values[] = array_map(
                fn ($value) => is_array($value) ? $this->jsonService->encode($value) : $value,
                $this->transformer->extract($object)
            );
        }

        $this->transaction->newQuery()
            ->insert($values)
        ;
    }

    public function insertOne(TransactionDtoInterface $dto): Transaction
    {
        $attributes = $this->transformer->extract($dto);
        $instance = $this->transaction->newInstance($attributes);
        $instance->saveQuietly();

        return $instance;
    }

    /**
     * @return Transaction[]
     */
    public function findBy(TransactionQueryInterface $query): array
    {
        return $this->transaction->newQuery()
            ->whereIn('uuid', $query->getUuids())
            ->get()
            ->all()
        ;
    }
}
