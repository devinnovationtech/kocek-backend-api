<?php

namespace App\Internal\Assembler;

use App\Internal\Dto\TransferDto;
use App\Internal\Dto\TransferDtoInterface;
use App\Internal\Service\UuidFactoryServiceInterface;
use Illuminate\Database\Eloquent\Model;

final class TransferDtoAssembler implements TransferDtoAssemblerInterface
{
    public function __construct(
        private readonly UuidFactoryServiceInterface $uuidService
    ) {
    }

    public function create(
        int $depositId,
        int $withdrawId,
        string $status,
        Model $fromModel,
        Model $toModel,
        int $discount,
        string $fee,
        ?string $uuid
    ): TransferDtoInterface {
        return new TransferDto(
            $uuid ?? $this->uuidService->uuid4(),
            $depositId,
            $withdrawId,
            $status,
            $fromModel->getMorphClass(),
            $fromModel->getKey(),
            $toModel->getMorphClass(),
            $toModel->getKey(),
            $discount,
            $fee
        );
    }
}
