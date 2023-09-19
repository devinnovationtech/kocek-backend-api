<?php

namespace App\External\Api;

use App\Interfaces\Wallet;
use App\Models\Transfer;
use App\Services\AssistantServiceInterface;
use App\Services\AtomicServiceInterface;
use App\Services\PrepareServiceInterface;
use App\Services\TransferServiceInterface;

/**
 * @internal
 */
final class TransferQueryHandler implements TransferQueryHandlerInterface
{
    public function __construct(
        private readonly AssistantServiceInterface $assistantService,
        private readonly TransferServiceInterface $transferService,
        private readonly PrepareServiceInterface $prepareService,
        private readonly AtomicServiceInterface $atomicService
    ) {
    }

    public function apply(array $objects): array
    {
        $wallets = $this->assistantService->getWallets(
            array_map(static fn (TransferQuery $query): Wallet => $query->getFrom(), $objects),
        );

        $values = array_map(
            fn (TransferQuery $query) => $this->prepareService->transferLazy(
                $query->getFrom(),
                $query->getTo(),
                Transfer::STATUS_TRANSFER,
                $query->getAmount(),
                $query->getMeta(),
            ),
            $objects
        );

        return $this->atomicService->blocks($wallets, fn () => $this->transferService->apply($values));
    }
}
