<?php

namespace App\Internal\Assembler;

use App\Internal\Query\TransferQuery;
use App\Internal\Query\TransferQueryInterface;

final class TransferQueryAssembler implements TransferQueryAssemblerInterface
{
    /**
     * @param non-empty-array<int|string, string> $uuids
     */
    public function create(array $uuids): TransferQueryInterface
    {
        return new TransferQuery($uuids);
    }
}
