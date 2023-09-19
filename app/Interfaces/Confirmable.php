<?php

namespace App\Interfaces;

use App\Exceptions\BalanceIsEmpty;
use App\Exceptions\ConfirmedInvalid;
use App\Exceptions\InsufficientFunds;
use App\Exceptions\UnconfirmedInvalid;
use App\Exceptions\WalletOwnerInvalid;
use App\Internal\Exceptions\ExceptionInterface;
use App\Internal\Exceptions\RecordNotFoundException;
use App\Internal\Exceptions\TransactionFailedException;
use App\Models\Transaction;
use Illuminate\Database\RecordsNotFoundException;

interface Confirmable
{
    /**
     * @throws BalanceIsEmpty
     * @throws InsufficientFunds
     * @throws ConfirmedInvalid
     * @throws WalletOwnerInvalid
     * @throws RecordNotFoundException
     * @throws RecordsNotFoundException
     * @throws TransactionFailedException
     * @throws ExceptionInterface
     */
    public function confirm(Transaction $transaction): bool;

    public function safeConfirm(Transaction $transaction): bool;

    /**
     * @throws UnconfirmedInvalid
     * @throws WalletOwnerInvalid
     * @throws RecordNotFoundException
     * @throws RecordsNotFoundException
     * @throws TransactionFailedException
     * @throws ExceptionInterface
     */
    public function resetConfirm(Transaction $transaction): bool;

    public function safeResetConfirm(Transaction $transaction): bool;

    /**
     * @throws ConfirmedInvalid
     * @throws WalletOwnerInvalid
     * @throws RecordNotFoundException
     * @throws RecordsNotFoundException
     * @throws TransactionFailedException
     * @throws ExceptionInterface
     */
    public function forceConfirm(Transaction $transaction): bool;
}
