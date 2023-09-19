<?php

namespace Tests\Units\Service;

use App\Internal\Exceptions\ExceptionInterface;
use App\Internal\Exceptions\ModelNotFoundException;
use App\Internal\Service\UuidFactoryServiceInterface;
use App\Services\WalletServiceInterface;
use Tests\Infra\Factories\BuyerFactory;
use Tests\Infra\Factories\UserMultiFactory;
use Tests\Infra\Models\Buyer;
use Tests\Infra\Models\UserMulti;
use Tests\TestCase;

/**
 * @internal
 */
final class WalletTest extends TestCase
{
    public function testFindBy(): void
    {
        /** @var Buyer $buyer */
        $buyer = BuyerFactory::new()->create();

        $uuidFactoryService = app(UuidFactoryServiceInterface::class);
        $walletService = app(WalletServiceInterface::class);

        $uuid = $uuidFactoryService->uuid4();

        self::assertNull($walletService->findBySlug($buyer, 'default'));
        self::assertNull($walletService->findByUuid($uuid));
        self::assertNull($walletService->findById(-1));

        $buyer->wallet->uuid = $uuid; // @hack
        $buyer->deposit(100);

        self::assertNotNull($walletService->findBySlug($buyer, 'default'));
        self::assertNotNull($walletService->findByUuid($uuid));
        self::assertNotNull($walletService->findById($buyer->wallet->getKey()));
    }

    public function testGetBySlug(): void
    {
        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionCode(ExceptionInterface::MODEL_NOT_FOUND);

        /** @var Buyer $buyer */
        $buyer = BuyerFactory::new()->create();
        $walletService = app(WalletServiceInterface::class);

        $walletService->getBySlug($buyer, 'default');
    }

    public function testGetById(): void
    {
        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionCode(ExceptionInterface::MODEL_NOT_FOUND);

        app(WalletServiceInterface::class)->getById(-1);
    }

    public function testCreateWalletWithUuid(): void
    {
        /** @var UserMulti $user */
        $user = UserMultiFactory::new()->create();

        $uuidFactoryService = app(UuidFactoryServiceInterface::class);

        /** @var string[] $uuids */
        $uuids = array_map(static fn () => $uuidFactoryService->uuid4(), range(1, 10));

        foreach ($uuids as $uuid) {
            $user->createWallet([
                'uuid' => $uuid,
                'name' => md5($uuid),
            ]);
        }

        self::assertSame(10, $user->wallets()->count());
        self::assertSame(10, $user->wallets()->whereIn('uuid', $uuids)->count());
    }

    public function testGetByUuid(): void
    {
        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionCode(ExceptionInterface::MODEL_NOT_FOUND);

        $uuidFactoryService = app(UuidFactoryServiceInterface::class);

        app(WalletServiceInterface::class)->getByUuid($uuidFactoryService->uuid4());
    }
}
