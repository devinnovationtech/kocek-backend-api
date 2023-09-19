<?php

namespace App\External\Contracts;

interface ExtraDtoInterface
{
    public function getDepositOption(): OptionDtoInterface;

    public function getWithdrawOption(): OptionDtoInterface;

    public function getUuid(): ?string;
}
