<?php

namespace Tests\Units\Api;

use App\External\Api\TransactionQuery;
use App\External\Api\TransactionQueryHandlerInterface;
use Tests\Infra\Factories\BuyerFactory;
use Tests\Infra\Models\Buyer;
use Tests\Infra\PackageModels\Transaction;
use Tests\TestCase;


/**
 * @internal
 */
final class TransactionHandlerTest extends TestCase
{
    public function testWalletNotExists(): void
    {
        /** @var TransactionQueryHandlerInterface $transactionHandler */
        $transactionHandler = app(TransactionQueryHandlerInterface::class);

        /** @var Buyer $buyer */    
        $buyer = BuyerFactory::new()->create();
        self::assertFalse($buyer->relationLoaded('wallet'));
        self::assertFalse($buyer->wallet->exists);

        $transactions = $transactionHandler->apply([
            TransactionQuery::createDeposit($buyer, 101, null),
            TransactionQuery::createDeposit($buyer, 100, null),
            TransactionQuery::createDeposit($buyer, 100, null),
            TransactionQuery::createDeposit($buyer, 100, null),
            TransactionQuery::createWithdraw($buyer, 400, null),
        ]);

        self::assertSame(1, $buyer->balanceInt);
        self::assertCount(5, $transactions);

        self::assertCount(
            4,
            array_filter($transactions, static fn ($t) => $t->type === Transaction::TYPE_DEPOSIT),
        );
        self::assertCount(
            1,
            array_filter($transactions, static fn ($t) => $t->type === Transaction::TYPE_WITHDRAW),
        );
    }
}
