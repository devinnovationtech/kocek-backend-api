<?php

namespace App\Interfaces;

use App\Exceptions\BalanceIsEmpty;
use App\Exceptions\InsufficientFunds;
use App\External\Contracts\ExtraDtoInterface;
use App\Internal\Exceptions\ExceptionInterface;
use App\Internal\Exceptions\RecordNotFoundException;
use App\Internal\Exceptions\TransactionFailedException;
use App\Models\Transfer;
use Illuminate\Database\RecordsNotFoundException;

interface Exchangeable
{
    /**
     * @param ExtraDtoInterface|array<mixed>|null $meta
     *
     * @throws BalanceIsEmpty
     * @throws InsufficientFunds
     * @throws RecordNotFoundException
     * @throws RecordsNotFoundException
     * @throws TransactionFailedException
     * @throws ExceptionInterface
     */
    public function exchange(Wallet $to, int|string $amount, ExtraDtoInterface|array|null $meta = null): Transfer;

    /**
     * @param ExtraDtoInterface|array<mixed>|null $meta
     */
    public function safeExchange(Wallet $to, int|string $amount, ExtraDtoInterface|array|null $meta = null): ?Transfer;

    /**
     * @param ExtraDtoInterface|array<mixed>|null $meta
     *
     * @throws RecordNotFoundException
     * @throws RecordsNotFoundException
     * @throws TransactionFailedException
     * @throws ExceptionInterface
     */
    public function forceExchange(Wallet $to, int|string $amount, ExtraDtoInterface|array|null $meta = null): Transfer;
}
