<?php

namespace Tests\Units\Service;

use App\Internal\Service\JsonService;
use Tests\TestCase;

/**
 * @internal
 */
final class JsonServiceTest extends TestCase
{
    public function testJsonEncodeSuccess(): void
    {
        $jsonService = app(JsonService::class);
        self::assertNull($jsonService->encode(null));
        self::assertJson((string) $jsonService->encode([1]));
    }

    public function testJsonEncodeFailed(): void
    {
        $jsonService = app(JsonService::class);
        $array = [1];
        $array[] = &$array;

        self::assertNull($jsonService->encode($array));
    }
}
