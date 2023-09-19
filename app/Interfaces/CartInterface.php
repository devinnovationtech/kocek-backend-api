<?php

namespace App\Interfaces;

use App\Internal\Dto\BasketDtoInterface;
use App\Internal\Exceptions\CartEmptyException;

/**
 * A kind of cart hydrate, needed for a smooth transition from a convenient dto to a less convenient internal dto.
 */
interface CartInterface
{
    /**
     * @throws CartEmptyException
     */
    public function getBasketDto(): BasketDtoInterface;
}
