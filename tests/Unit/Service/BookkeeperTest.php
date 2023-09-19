<?php

namespace Tests\Units\Service;

use App\Services\BookkeeperService;
use Tests\Infra\Factories\BuyerFactory;
use Tests\Infra\Models\Buyer;
use Tests\TestCase;

/**
 * @internal
 */
final class BookkeeperTest extends TestCase
{
    public function testSync(): void
    {
        /** @var Buyer $buyer */
        $buyer = BuyerFactory::new()->create();

        $booker = app(BookkeeperService::class);
        self::assertTrue($booker->sync($buyer->wallet, 42));
        self::assertSame('42', $booker->amount($buyer->wallet));
    }

    public function testAmount(): void
    {
        /** @var Buyer $buyer */
        $buyer = BuyerFactory::new()->create();
        $buyer->deposit(42);
        $buyer->withdraw(11);
        $buyer->deposit(1);

        $booker = app(BookkeeperService::class);
        self::assertSame('32', $booker->amount($buyer->wallet));
    }

    public function testIncrease(): void
    {
        /** @var Buyer $buyer */
        $buyer = BuyerFactory::new()->create();

        $booker = app(BookkeeperService::class);
        self::assertSame('5', $booker->increase($buyer->wallet, 5));
        self::assertTrue($booker->forget($buyer->wallet));
        self::assertSame('0', $booker->amount($buyer->wallet));
    }

    public function testMultiIncrease(): void
    {
        /** @var Buyer $buyer */
        $buyer = BuyerFactory::new()->create();

        $booker = app(BookkeeperService::class);
        self::assertSame(
            [
                $buyer->wallet->uuid => '5',
            ],
            $booker->multiIncrease([
                $buyer->wallet->uuid => $buyer->wallet,
            ], [
                $buyer->wallet->uuid => 5,
            ]),
        );
        self::assertTrue($booker->forget($buyer->wallet));
        self::assertSame('0', $booker->amount($buyer->wallet));
    }
}
