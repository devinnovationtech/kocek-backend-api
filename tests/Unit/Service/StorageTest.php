<?php

namespace Tests\Units\Service;

use App\Internal\Decorator\StorageServiceLockDecorator;
use App\Internal\Exceptions\ExceptionInterface;
use App\Internal\Exceptions\RecordNotFoundException;
use App\Internal\Service\StorageService;
use Tests\TestCase;

/**
 * @internal
 */
final class StorageTest extends TestCase
{
    public function testFlush(): void
    {
        $this->expectException(RecordNotFoundException::class);
        $this->expectExceptionCode(ExceptionInterface::RECORD_NOT_FOUND);
        $storage = app(StorageService::class);

        self::assertTrue($storage->sync('hello', 34));
        self::assertTrue($storage->sync('world', 42));
        self::assertSame('42', $storage->get('world'));
        self::assertSame('34', $storage->get('hello'));
        self::assertTrue($storage->flush());

        $storage->get('hello'); // record not found
    }

    public function testDecorator(): void
    {
        $this->expectException(RecordNotFoundException::class);
        $this->expectExceptionCode(ExceptionInterface::RECORD_NOT_FOUND);
        $storage = app(StorageServiceLockDecorator::class);

        self::assertTrue($storage->sync('hello', 34));
        self::assertTrue($storage->sync('world', 42));
        self::assertSame('42', $storage->get('world'));
        self::assertSame('34', $storage->get('hello'));
        self::assertTrue($storage->flush());

        $storage->get('hello'); // record not found
    }

    public function testIncreaseDecorator(): void
    {
        $storage = app(StorageServiceLockDecorator::class);

        $storage->multiSync([
            'hello' => 34,
        ]);

        self::assertSame('34', $storage->get('hello'));
        self::assertSame('42', $storage->increase('hello', 8));
    }
}
