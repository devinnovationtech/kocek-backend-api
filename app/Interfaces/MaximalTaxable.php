<?php

namespace App\Interfaces;

interface MaximalTaxable extends Taxable
{
    public function getMaximalFee(): float|int;
}
