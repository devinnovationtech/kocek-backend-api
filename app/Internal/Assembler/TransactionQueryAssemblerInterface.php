<?php

namespace App\Internal\Assembler;

use App\Internal\Query\TransactionQueryInterface;

interface TransactionQueryAssemblerInterface
{
    /**
     * @param non-empty-array<int|string, string> $uuids
     */
    public function create(array $uuids): TransactionQueryInterface;
}
