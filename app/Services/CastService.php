<?php

namespace App\Services;

use App\Interfaces\Wallet;
use App\Internal\Assembler\WalletCreatedEventAssemblerInterface;
use App\Internal\Exceptions\ExceptionInterface;
use App\Internal\Service\DatabaseServiceInterface;
use App\Internal\Service\DispatcherServiceInterface;
use App\Models\Wallet as WalletModel;
use Illuminate\Database\Eloquent\Model;

/**
 * @internal
 */
final class CastService implements CastServiceInterface
{
    public function __construct(
        private readonly WalletCreatedEventAssemblerInterface $walletCreatedEventAssembler,
        private readonly DispatcherServiceInterface $dispatcherService,
        private readonly DatabaseServiceInterface $databaseService
    ) {
    }

    /**
     * @throws ExceptionInterface
     */
    public function getWallet(Wallet $object, bool $save = true): WalletModel
    {
        $wallet = $this->getModel($object);
        if (! ($wallet instanceof WalletModel)) {
            $wallet = $wallet->getAttribute('wallet');
            assert($wallet instanceof WalletModel);
        }

        if ($save && ! $wallet->exists) {
            $this->databaseService->transaction(function () use ($wallet) {
                $result = $wallet->saveQuietly();
                $this->dispatcherService->dispatch($this->walletCreatedEventAssembler->create($wallet));

                return $result;
            });
        }

        return $wallet;
    }

    public function getHolder(Model|Wallet $object): Model
    {
        return $this->getModel($object instanceof WalletModel ? $object->holder : $object);
    }

    public function getModel(object $object): Model
    {
        assert($object instanceof Model);

        return $object;
    }
}
