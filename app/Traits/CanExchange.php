<?php

namespace App\Traits;

use App\Exceptions\BalanceIsEmpty;
use App\Exceptions\InsufficientFunds;
use App\External\Contracts\ExtraDtoInterface;
use App\Interfaces\Wallet;
use App\Internal\Assembler\ExtraDtoAssemblerInterface;
use App\Internal\Assembler\TransferLazyDtoAssemblerInterface;
use App\Internal\Exceptions\ExceptionInterface;
use App\Internal\Exceptions\RecordNotFoundException;
use App\Internal\Exceptions\TransactionFailedException;
use App\Internal\Service\MathServiceInterface;
use App\Models\Transfer;
use App\Services\AtomicServiceInterface;
use App\Services\CastServiceInterface;
use App\Services\ConsistencyServiceInterface;
use App\Services\ExchangeServiceInterface;
use App\Services\PrepareServiceInterface;
use App\Services\TaxServiceInterface;
use App\Services\TransferServiceInterface;
use Illuminate\Database\RecordsNotFoundException;

/**
 * @psalm-require-extends \Illuminate\Database\Eloquent\Model
 */
trait CanExchange
{
    /**
     * @throws BalanceIsEmpty
     * @throws InsufficientFunds
     * @throws RecordNotFoundException
     * @throws RecordsNotFoundException
     * @throws TransactionFailedException
     * @throws ExceptionInterface
     */
    public function exchange(Wallet $to, int|string $amount, ExtraDtoInterface|array|null $meta = null): Transfer
    {
        return app(AtomicServiceInterface::class)->block($this, function () use ($to, $amount, $meta): Transfer {
            app(ConsistencyServiceInterface::class)->checkPotential($this, $amount);

            return $this->forceExchange($to, $amount, $meta);
        });
    }

    public function safeExchange(Wallet $to, int|string $amount, ExtraDtoInterface|array|null $meta = null): ?Transfer
    {
        try {
            return $this->exchange($to, $amount, $meta);
        } catch (ExceptionInterface) {
            return null;
        }
    }

    /**
     * @throws RecordNotFoundException
     * @throws RecordsNotFoundException
     * @throws TransactionFailedException
     * @throws ExceptionInterface
     */
    public function forceExchange(Wallet $to, int|string $amount, ExtraDtoInterface|array|null $meta = null): Transfer
    {
        return app(AtomicServiceInterface::class)->block($this, function () use ($to, $amount, $meta): Transfer {
            $extraAssembler = app(ExtraDtoAssemblerInterface::class);
            $prepareService = app(PrepareServiceInterface::class);
            $mathService = app(MathServiceInterface::class);
            $castService = app(CastServiceInterface::class);
            $taxService = app(TaxServiceInterface::class);
            $fee = $taxService->getFee($to, $amount);
            $rate = app(ExchangeServiceInterface::class)->convertTo(
                $castService->getWallet($this)
                    ->getCurrencyAttribute(),
                $castService->getWallet($to)
                    ->currency,
                1
            );

            $extraDto = $extraAssembler->create($meta);
            $withdrawOption = $extraDto->getWithdrawOption();
            $depositOption = $extraDto->getDepositOption();
            $withdrawDto = $prepareService->withdraw(
                $this,
                $mathService->add($amount, $fee),
                $withdrawOption->getMeta(),
                $withdrawOption->isConfirmed(),
                $withdrawOption->getUuid(),
            );
            $depositDto = $prepareService->deposit(
                $to,
                $mathService->floor($mathService->mul($amount, $rate, 1)),
                $depositOption->getMeta(),
                $depositOption->isConfirmed(),
                $depositOption->getUuid(),
            );
            $transferLazyDto = app(TransferLazyDtoAssemblerInterface::class)->create(
                $this,
                $to,
                0,
                $fee,
                $withdrawDto,
                $depositDto,
                Transfer::STATUS_EXCHANGE,
                $extraDto->getUuid()
            );

            $transfers = app(TransferServiceInterface::class)->apply([$transferLazyDto]);

            return current($transfers);
        });
    }
}
