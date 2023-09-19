<?php

namespace App\Services;

use App\Interfaces\Customer;
use App\Internal\Dto\BasketDtoInterface;

/**
 * Ad hoc solution... Needed for internal purposes only. Helps to optimize greedy queries inside laravel.
 *
 * @api
 */
interface EagerLoaderServiceInterface
{
    public function loadWalletsByBasket(Customer $customer, BasketDtoInterface $basketDto): void;
}
