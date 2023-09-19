<?php

namespace App\Interfaces;

interface Discount
{
    public function getPersonalDiscount(Customer $customer): float|int;
}
