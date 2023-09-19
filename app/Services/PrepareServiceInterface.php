<?php

namespace App\Services;

use App\Exceptions\AmountInvalid;
use App\External\Contracts\ExtraDtoInterface;
use App\Interfaces\Wallet;
use App\Internal\Dto\TransactionDtoInterface;
use App\Internal\Dto\TransferLazyDtoInterface;
use App\Models\Wallet as WalletModel;

/**
 * @api
 */
interface PrepareServiceInterface
{
    /**
     * @param null|array<mixed> $meta
     *
     * @throws AmountInvalid
     */
    public function deposit(
        Wallet $wallet,
        float|int|string $amount,
        ?array $meta,
        bool $confirmed = true,
        ?string $uuid = null
    ): TransactionDtoInterface;

    /**
     * @param null|array<mixed> $meta
     *
     * @throws AmountInvalid
     */
    public function withdraw(
        Wallet $wallet,
        float|int|string $amount,
        ?array $meta,
        bool $confirmed = true,
        ?string $uuid = null
    ): TransactionDtoInterface;

    /**
     * @param ExtraDtoInterface|array<mixed>|null $meta
     *
     * @throws AmountInvalid
     */
    public function transferLazy(
        Wallet $from,
        Wallet $to,
        string $status,
        float|int|string $amount,
        ExtraDtoInterface|array|null $meta = null
    ): TransferLazyDtoInterface;

    /**
     * @param ExtraDtoInterface|array<mixed>|null $meta
     *
     * @throws AmountInvalid
     */
    public function transferExtraLazy(
        Wallet $from,
        WalletModel $fromWallet,
        Wallet $to,
        WalletModel $toWallet,
        string $status,
        float|int|string $amount,
        ExtraDtoInterface|array|null $meta = null
    ): TransferLazyDtoInterface;
}
