<?php

namespace App\Internal\Assembler;

use App\Internal\Events\BalanceUpdatedEventInterface;
use App\Models\Wallet;

interface BalanceUpdatedEventAssemblerInterface
{
    public function create(Wallet $wallet): BalanceUpdatedEventInterface;
}
