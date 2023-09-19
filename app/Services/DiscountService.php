<?php

namespace App\Services;

use App\Interfaces\Customer;
use App\Interfaces\Discount;
use App\Interfaces\Wallet;

/**
 * @internal
 */
final class DiscountService implements DiscountServiceInterface
{
    public function getDiscount(Wallet $customer, Wallet $product): int
    {
        if ($customer instanceof Customer && $product instanceof Discount) {
            return (int) $product->getPersonalDiscount($customer);
        }

        return 0;
    }
}
