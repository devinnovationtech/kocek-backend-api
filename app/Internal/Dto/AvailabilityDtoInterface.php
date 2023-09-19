<?php

namespace App\Internal\Dto;

use App\Interfaces\Customer;

interface AvailabilityDtoInterface
{
    public function getBasketDto(): BasketDtoInterface;

    public function getCustomer(): Customer;

    public function isForce(): bool;
}
