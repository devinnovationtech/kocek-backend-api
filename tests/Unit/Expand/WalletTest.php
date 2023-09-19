<?php

namespace Tests\Units\Expand;

use Tests\Infra\Factories\BuyerFactory;
use Tests\Infra\Models\Buyer;
use Tests\Infra\PackageModels\MyWallet;
use Tests\TestCase;

/**
 * @internal
 */
final class WalletTest extends TestCase
{
    public function testAddMethod(): void
    {
        config([
            'wallet.wallet.model' => MyWallet::class,
        ]);

        /** @var Buyer $buyer */
        $buyer = BuyerFactory::new()->create();

        /** @var MyWallet $wallet */
        $wallet = $buyer->wallet;

        self::assertSame('hello world', $wallet->helloWorld());
    }
}
