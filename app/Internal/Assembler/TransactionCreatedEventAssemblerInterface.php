<?php

namespace App\Internal\Assembler;

use App\Internal\Events\TransactionCreatedEventInterface;
use App\Models\Transaction;

interface TransactionCreatedEventAssemblerInterface
{
    public function create(Transaction $transaction): TransactionCreatedEventInterface;
}
