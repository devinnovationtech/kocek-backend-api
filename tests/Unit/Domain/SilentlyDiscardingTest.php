<?php

namespace Tests\Units\Domain;

use App\Internal\Service\ClockServiceInterface;
use Tests\Infra\Factories\BuyerFactory;
use Tests\Infra\Factories\UserFactory;
use Tests\Infra\Factories\UserMultiFactory;
use Tests\Infra\Models\Buyer;
use Tests\Infra\Models\User;
use Tests\Infra\Models\UserMulti;
use Tests\Infra\Services\ClockFakeService;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Model;

/**
 * @internal
 */
final class SilentlyDiscardingTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Model::preventSilentlyDiscardingAttributes();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Model::preventSilentlyDiscardingAttributes(false);
    }

    public function testDepositSilentlyDiscarding(): void
    {
        /** @var Buyer $buyer */
        $buyer = BuyerFactory::new()->create();
        self::assertFalse($buyer->relationLoaded('wallet'));
        $buyer->deposit(1);

        self::assertTrue($buyer->relationLoaded('wallet'));
        self::assertTrue($buyer->wallet->exists);
        self::assertSame(1, $buyer->balanceInt);
    }

    public function testTransferSilentlyDiscarding(): void
    {
        /**
         * @var User $first
         * @var User $second
         */
        [$first, $second] = UserFactory::times(2)->create();
        self::assertNotSame($first->getKey(), $second->getKey());

        self::assertNotNull($first->deposit(1000));
        self::assertSame(1000, $first->balanceInt);

        self::assertNotNull($first->transfer($second, 500));
        self::assertSame(500, $first->balanceInt);
        self::assertSame(500, $second->balanceInt);
    }

    public function testMultiWalletSilentlyDiscarding(): void
    {
        $this->app?->bind(ClockServiceInterface::class, ClockFakeService::class);

        /** @var UserMulti $user */
        $user = UserMultiFactory::new()->create();
        $dateTime = app(ClockServiceInterface::class)->now();

        $wallet = $user->createWallet([
            'name' => 'hello',
            'created_at' => $dateTime->getTimestamp(),
            'updated_at' => $dateTime->getTimestamp(),
        ]);

        self::assertCount(1, $user->wallets);
        self::assertSame($dateTime->getTimestamp(), $wallet->created_at->getTimestamp());
        self::assertSame($dateTime->getTimestamp(), $wallet->updated_at->getTimestamp());
    }
}
