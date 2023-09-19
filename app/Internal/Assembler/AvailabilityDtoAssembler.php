<?php

namespace App\Internal\Assembler;

use App\Interfaces\Customer;
use App\Internal\Dto\AvailabilityDto;
use App\Internal\Dto\AvailabilityDtoInterface;
use App\Internal\Dto\BasketDtoInterface;

final class AvailabilityDtoAssembler implements AvailabilityDtoAssemblerInterface
{
    public function create(Customer $customer, BasketDtoInterface $basketDto, bool $force): AvailabilityDtoInterface
    {
        return new AvailabilityDto($customer, $basketDto, $force);
    }
}
