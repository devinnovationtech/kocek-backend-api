<?php

namespace App\Services;

use App\Interfaces\Customer;
use App\Internal\Dto\BasketDtoInterface;
use App\Models\Transfer;

/**
 * @api
 */
interface PurchaseServiceInterface
{
    /**
     * @return Transfer[]
     */
    public function already(Customer $customer, BasketDtoInterface $basketDto, bool $gifts = false): array;
}
