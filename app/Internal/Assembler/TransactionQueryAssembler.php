<?php

namespace App\Internal\Assembler;

use App\Internal\Query\TransactionQuery;
use App\Internal\Query\TransactionQueryInterface;

final class TransactionQueryAssembler implements TransactionQueryAssemblerInterface
{
    /**
     * @param non-empty-array<int|string, string> $uuids
     */
    public function create(array $uuids): TransactionQueryInterface
    {
        return new TransactionQuery($uuids);
    }
}
