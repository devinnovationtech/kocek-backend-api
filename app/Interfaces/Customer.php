<?php

namespace App\Interfaces;

use App\Exceptions\BalanceIsEmpty;
use App\Exceptions\InsufficientFunds;
use App\Exceptions\ProductEnded;
use App\Internal\Exceptions\ExceptionInterface;
use App\Internal\Exceptions\ModelNotFoundException;
use App\Internal\Exceptions\RecordNotFoundException;
use App\Internal\Exceptions\TransactionFailedException;
use App\Models\Transfer;
use Illuminate\Database\RecordsNotFoundException;

interface Customer extends Wallet
{
    /**
     * @throws ProductEnded
     * @throws BalanceIsEmpty
     * @throws InsufficientFunds
     * @throws RecordNotFoundException
     * @throws RecordsNotFoundException
     * @throws TransactionFailedException
     * @throws ExceptionInterface
     */
    public function payFree(ProductInterface $product): Transfer;

    public function safePay(ProductInterface $product, bool $force = false): ?Transfer;

    /**
     * @throws ProductEnded
     * @throws BalanceIsEmpty
     * @throws InsufficientFunds
     * @throws RecordNotFoundException
     * @throws RecordsNotFoundException
     * @throws TransactionFailedException
     * @throws ExceptionInterface
     */
    public function pay(ProductInterface $product, bool $force = false): Transfer;

    /**
     * @throws ProductEnded
     * @throws RecordNotFoundException
     * @throws RecordsNotFoundException
     * @throws TransactionFailedException
     * @throws ExceptionInterface
     */
    public function forcePay(ProductInterface $product): Transfer;

    public function safeRefund(ProductInterface $product, bool $force = false, bool $gifts = false): bool;

    /**
     * @throws BalanceIsEmpty
     * @throws InsufficientFunds
     * @throws RecordNotFoundException
     * @throws RecordsNotFoundException
     * @throws TransactionFailedException
     * @throws ModelNotFoundException
     * @throws ExceptionInterface
     */
    public function refund(ProductInterface $product, bool $force = false, bool $gifts = false): bool;

    /**
     * @throws RecordNotFoundException
     * @throws RecordsNotFoundException
     * @throws TransactionFailedException
     * @throws ModelNotFoundException
     * @throws ExceptionInterface
     */
    public function forceRefund(ProductInterface $product, bool $gifts = false): bool;

    public function safeRefundGift(ProductInterface $product, bool $force = false): bool;

    /**
     * @throws BalanceIsEmpty
     * @throws InsufficientFunds
     * @throws RecordNotFoundException
     * @throws RecordsNotFoundException
     * @throws TransactionFailedException
     * @throws ModelNotFoundException
     * @throws ExceptionInterface
     */
    public function refundGift(ProductInterface $product, bool $force = false): bool;

    /**
     * @throws RecordNotFoundException
     * @throws RecordsNotFoundException
     * @throws TransactionFailedException
     * @throws ModelNotFoundException
     * @throws ExceptionInterface
     */
    public function forceRefundGift(ProductInterface $product): bool;

    /**
     * @throws ProductEnded
     * @throws BalanceIsEmpty
     * @throws InsufficientFunds
     * @throws RecordNotFoundException
     * @throws RecordsNotFoundException
     * @throws TransactionFailedException
     * @throws ExceptionInterface
     *
     * @return non-empty-array<Transfer>
     */
    public function payFreeCart(CartInterface $cart): array;

    /**
     * @return Transfer[]
     */
    public function safePayCart(CartInterface $cart, bool $force = false): array;

    /**
     * @throws ProductEnded
     * @throws BalanceIsEmpty
     * @throws InsufficientFunds
     * @throws RecordNotFoundException
     * @throws RecordsNotFoundException
     * @throws TransactionFailedException
     * @throws ExceptionInterface
     *
     * @return non-empty-array<Transfer>
     */
    public function payCart(CartInterface $cart, bool $force = false): array;

    /**
     * @throws ProductEnded
     * @throws RecordNotFoundException
     * @throws RecordsNotFoundException
     * @throws TransactionFailedException
     * @throws ExceptionInterface
     *
     * @return non-empty-array<Transfer>
     */
    public function forcePayCart(CartInterface $cart): array;

    public function safeRefundCart(CartInterface $cart, bool $force = false, bool $gifts = false): bool;

    /**
     * @throws BalanceIsEmpty
     * @throws InsufficientFunds
     * @throws RecordNotFoundException
     * @throws RecordsNotFoundException
     * @throws TransactionFailedException
     * @throws ModelNotFoundException
     * @throws ExceptionInterface
     */
    public function refundCart(CartInterface $cart, bool $force = false, bool $gifts = false): bool;

    /**
     * @throws RecordNotFoundException
     * @throws RecordsNotFoundException
     * @throws TransactionFailedException
     * @throws ModelNotFoundException
     * @throws ExceptionInterface
     */
    public function forceRefundCart(CartInterface $cart, bool $gifts = false): bool;

    public function safeRefundGiftCart(CartInterface $cart, bool $force = false): bool;

    /**
     * @throws BalanceIsEmpty
     * @throws InsufficientFunds
     * @throws RecordNotFoundException
     * @throws RecordsNotFoundException
     * @throws TransactionFailedException
     * @throws ModelNotFoundException
     * @throws ExceptionInterface
     */
    public function refundGiftCart(CartInterface $cart, bool $force = false): bool;

    /**
     * @throws RecordNotFoundException
     * @throws RecordsNotFoundException
     * @throws TransactionFailedException
     * @throws ModelNotFoundException
     * @throws ExceptionInterface
     */
    public function forceRefundGiftCart(CartInterface $cart): bool;

    /**
     * Checks acquired product your wallet.
     *
     * @deprecated The method is slow and will be removed in the future
     * @see PurchaseServiceInterface
     */
    public function paid(ProductInterface $product, bool $gifts = false): ?Transfer;
}
