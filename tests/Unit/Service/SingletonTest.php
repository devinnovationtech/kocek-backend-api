<?php

namespace Tests\Units\Service;

use App\Internal\Service\DatabaseServiceInterface;
use App\Internal\Service\MathServiceInterface;
use App\Objects\Cart;
use Tests\Infra\PackageModels\Transaction;
use Tests\Infra\PackageModels\Transfer;
use Tests\Infra\PackageModels\Wallet;
use Tests\TestCase;

/**
 * @internal
 */
final class SingletonTest extends TestCase
{
    public function testCart(): void
    {
        self::assertNotSame($this->getRefId(Cart::class), $this->getRefId(Cart::class));
    }

    public function testMathInterface(): void
    {
        self::assertSame($this->getRefId(MathServiceInterface::class), $this->getRefId(MathServiceInterface::class));
    }

    public function testTransaction(): void
    {
        self::assertNotSame($this->getRefId(Transaction::class), $this->getRefId(Transaction::class));
    }

    public function testTransfer(): void
    {
        self::assertNotSame($this->getRefId(Transfer::class), $this->getRefId(Transfer::class));
    }

    public function testWallet(): void
    {
        self::assertNotSame($this->getRefId(Wallet::class), $this->getRefId(Wallet::class));
    }

    public function testDatabaseService(): void
    {
        self::assertSame(
            $this->getRefId(DatabaseServiceInterface::class),
            $this->getRefId(DatabaseServiceInterface::class)
        );
    }

    private function getRefId(string $object): string
    {
        return spl_object_hash(app($object));
    }
}
