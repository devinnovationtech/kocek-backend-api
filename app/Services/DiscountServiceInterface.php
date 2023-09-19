<?php

namespace App\Services;

use App\Interfaces\Wallet;

/**
 * @api
 */
interface DiscountServiceInterface
{
    public function getDiscount(Wallet $customer, Wallet $product): int;
}
