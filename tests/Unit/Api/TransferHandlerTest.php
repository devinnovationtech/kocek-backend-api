<?php

namespace Tests\Units\Api;

use App\External\Api\TransferQuery;
use App\External\Api\TransferQueryHandlerInterface;
use Tests\Infra\Factories\BuyerFactory;
use Tests\Infra\Models\Buyer;
use Tests\TestCase;


/**
 * @internal
 */
final class TransferHandlerTest extends TestCase
{
    public function testWalletNotExists(): void
    {
        /** @var TransferQueryHandlerInterface $transferQueryHandler */
        $transferQueryHandler = app(TransferQueryHandlerInterface::class);

        /** @var Buyer $from */
        /** @var Buyer $to */
        [$from, $to] = BuyerFactory::times(2)->create();

        self::assertFalse($from->relationLoaded('wallet'));
        self::assertFalse($from->wallet->exists);
        self::assertFalse($to->relationLoaded('wallet'));
        self::assertFalse($to->wallet->exists);

        $transfers = $transferQueryHandler->apply([
            new TransferQuery($from, $to, 100, null),
            new TransferQuery($from, $to, 100, null),
            new TransferQuery($to, $from, 50, null),
        ]);

        self::assertSame(-150, $from->balanceInt);
        self::assertSame(150, $to->balanceInt);
        self::assertCount(3, $transfers);
    }
}
