<?php

namespace Tests\Units\Service;

use App\Internal\Exceptions\ExceptionInterface;
use App\Internal\Exceptions\TransactionFailedException;
use App\Internal\Service\DatabaseServiceInterface;
use Tests\TestCase;

/**
 * @internal
 */
final class DatabaseTest extends TestCase
{
    /**
     * @throws ExceptionInterface
     */
    public function testCheckCode(): void
    {
        $this->expectException(TransactionFailedException::class);
        $this->expectExceptionCode(ExceptionInterface::TRANSACTION_FAILED);
        $this->expectExceptionMessage('Transaction failed. Message: hello');

        app(DatabaseServiceInterface::class)->transaction(static function (): never {
            throw new \RuntimeException('hello');
        });
    }
}
