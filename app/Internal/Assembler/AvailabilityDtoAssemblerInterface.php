<?php

namespace App\Internal\Assembler;

use App\Interfaces\Customer;
use App\Internal\Dto\AvailabilityDtoInterface;
use App\Internal\Dto\BasketDtoInterface;

interface AvailabilityDtoAssemblerInterface
{
    public function create(Customer $customer, BasketDtoInterface $basketDto, bool $force): AvailabilityDtoInterface;
}
