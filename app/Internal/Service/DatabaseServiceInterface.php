<?php

namespace App\Internal\Service;

use App\Internal\Exceptions\ExceptionInterface;
use App\Internal\Exceptions\TransactionFailedException;
use Illuminate\Database\RecordsNotFoundException;

interface DatabaseServiceInterface
{
    /**
     * @template T
     * @param callable(): T $callback
     * @return T
     *
     * @throws RecordsNotFoundException
     * @throws TransactionFailedException
     * @throws ExceptionInterface
     */
    public function transaction(callable $callback): mixed;
}
