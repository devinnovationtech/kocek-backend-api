<?php

namespace App\Internal\Query;

interface TransactionQueryInterface
{
    /**
     * @return non-empty-array<int|string, string>
     */
    public function getUuids(): array;
}
