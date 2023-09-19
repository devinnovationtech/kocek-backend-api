<?php

namespace Tests\Units\Service;

use Tests\TestCase;
use App\WalletConfigure;

/**
 * @internal
 */
final class WalletConfigureTest extends TestCase
{
    public function testIgnoreMigrations(): void
    {
        self::assertTrue(WalletConfigure::isRunsMigrations());

        WalletConfigure::ignoreMigrations();
        self::assertFalse(WalletConfigure::isRunsMigrations());

        WalletConfigure::reset();
        self::assertTrue(WalletConfigure::isRunsMigrations());
    }
}
