<?php

namespace App\Services;

use App\Interfaces\Wallet;

/**
 * @api
 */
interface TaxServiceInterface
{
    public function getFee(Wallet $wallet, float|int|string $amount): string;
}
