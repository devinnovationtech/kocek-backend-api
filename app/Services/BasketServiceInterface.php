<?php

namespace App\Services;

use App\Internal\Dto\AvailabilityDtoInterface;

/**
 * @api
 */
interface BasketServiceInterface
{
    /**
     * A quick way to check stock. Able to check in batches, necessary for quick payments.
     */
    public function availability(AvailabilityDtoInterface $availabilityDto): bool;
}
