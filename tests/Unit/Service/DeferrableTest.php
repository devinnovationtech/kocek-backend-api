<?php

namespace Tests\Units\Service;

use Tests\TestCase;
use App\Providers\WalletServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;

/**
 * @internal
 */
final class DeferrableTest extends TestCase
{
    public function testCheckDeferrableProvider(): void
    {
        $walletServiceProvider = app()
            ->resolveProvider(WalletServiceProvider::class)
        ;

        self::assertInstanceOf(DeferrableProvider::class, $walletServiceProvider);
        self::assertNotEmpty($walletServiceProvider->provides());
    }
}
