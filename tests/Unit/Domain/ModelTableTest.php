<?php

namespace Tests\Units\Domain;

use Tests\Infra\Factories\ManagerFactory;
use Tests\Infra\Factories\UserFactory;
use Tests\Infra\Models\Manager;
use Tests\Infra\Models\User;
use Tests\TestCase;

/**
 * @internal
 */
final class ModelTableTest extends TestCase
{
    public function testWalletTableName(): void
    {
        /** @var User $user */
        $user = UserFactory::new()->create();
        self::assertSame('wallet', $user->wallet->getTable());
    }

    public function testTransactionTableName(): void
    {
        /** @var User $user */
        $user = UserFactory::new()->create();
        $transaction = $user->deposit(100);
        self::assertSame('transaction', $transaction->getTable());
    }

    public function testTransferTableName(): void
    {
        /**
         * @var User $user1
         * @var User $user2
         */
        [$user1, $user2] = UserFactory::times(2)->create();
        $user1->deposit(1000);
        $transfer = $user1->transfer($user2, 1000);
        self::assertSame('transfer', $transfer->getTable());

        /** @var Manager $manager */
        $manager = ManagerFactory::new()->create();
        $user2->transfer($manager, 1000);
        self::assertSame(1000, $manager->balanceInt);
    }
}
