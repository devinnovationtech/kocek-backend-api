<?php

namespace App\Interfaces;

interface MinimalTaxable extends Taxable
{
    public function getMinimalFee(): float|int;
}
