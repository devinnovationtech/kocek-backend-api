<?php

namespace App\Internal\Assembler;

use App\Internal\Events\WalletCreatedEventInterface;
use App\Models\Wallet;

interface WalletCreatedEventAssemblerInterface
{
    public function create(Wallet $wallet): WalletCreatedEventInterface;
}
