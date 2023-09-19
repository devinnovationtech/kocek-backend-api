<?php

namespace App\Internal\Assembler;

use App\Internal\Dto\TransactionDto;
use App\Internal\Dto\TransactionDtoInterface;
use App\Internal\Service\UuidFactoryServiceInterface;
use Illuminate\Database\Eloquent\Model;

final class TransactionDtoAssembler implements TransactionDtoAssemblerInterface
{
    public function __construct(
        private readonly UuidFactoryServiceInterface $uuidService
    ) {
    }

    public function create(
        Model $payable,
        int $walletId,
        string $type,
        float|int|string $amount,
        bool $confirmed,
        ?array $meta,
        ?string $uuid
    ): TransactionDtoInterface {
        return new TransactionDto(
            $uuid ?? $this->uuidService->uuid4(),
            $payable->getMorphClass(),
            $payable->getKey(),
            $walletId,
            $type,
            $amount,
            $confirmed,
            $meta
        );
    }
}
