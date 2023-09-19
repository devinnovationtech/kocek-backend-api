<?php

namespace App\Internal\Assembler;

use App\Internal\Query\TransferQueryInterface;

interface TransferQueryAssemblerInterface
{
    /**
     * @param non-empty-array<int|string, string> $uuids
     */
    public function create(array $uuids): TransferQueryInterface;
}
