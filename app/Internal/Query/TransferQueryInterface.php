<?php

namespace App\Internal\Query;

interface TransferQueryInterface
{
    /**
     * @return non-empty-array<int|string, string>
     */
    public function getUuids(): array;
}
