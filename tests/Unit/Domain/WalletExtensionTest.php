<?php

namespace Tests\Units\Domain;

use App\Internal\Transform\TransactionDtoTransformerInterface;
use Tests\Infra\Factories\BuyerFactory;
use Tests\Infra\Models\Buyer;
use Tests\Infra\PackageModels\Transaction;
use Tests\Infra\PackageModels\TransactionMoney;
use Tests\TestCase;
use Tests\Infra\Transform\TransactionDtoTransformerCustom;

/**
 * @internal
 */
final class WalletExtensionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->app?->bind(TransactionDtoTransformerInterface::class, TransactionDtoTransformerCustom::class);
    }

    public function testCustomAttribute(): void
    {
        /** @var Buyer $buyer */
        $buyer = BuyerFactory::new()->create();
        self::assertFalse($buyer->relationLoaded('wallet'));
        $transaction = $buyer->deposit(1000, [
            'bank_method' => 'VietComBank',
        ]);

        self::assertTrue($transaction->getKey() > 0);
        self::assertSame($transaction->amountInt, $buyer->balanceInt);
        self::assertInstanceOf(Transaction::class, $transaction);
        self::assertSame('VietComBank', $transaction->bank_method);
    }

    public function testTransactionMoneyAttribute(): void
    {
        $this->app?->bind(\App\Models\Transaction::class, TransactionMoney::class);

        /** @var Buyer $buyer */
        $buyer = BuyerFactory::new()->create();
        self::assertFalse($buyer->relationLoaded('wallet'));
        /** @var TransactionMoney $transaction */
        $transaction = $buyer->deposit(1000, [
            'currency' => 'EUR',
        ]);

        self::assertTrue($transaction->getKey() > 0);
        self::assertSame($transaction->amountInt, $buyer->balanceInt);
        self::assertInstanceOf(TransactionMoney::class, $transaction);
        self::assertSame('1000', $transaction->currency->getAmount());
        self::assertSame('EUR', $transaction->currency->getCurrency()->getCode());
    }

    public function testNoCustomAttribute(): void
    {
        /** @var Buyer $buyer */
        $buyer = BuyerFactory::new()->create();
        self::assertFalse($buyer->relationLoaded('wallet'));
        $transaction = $buyer->deposit(1000);

        self::assertSame($transaction->amountInt, $buyer->balanceInt);
        self::assertInstanceOf(Transaction::class, $transaction);
        self::assertNull($transaction->bank_method);
    }
}
