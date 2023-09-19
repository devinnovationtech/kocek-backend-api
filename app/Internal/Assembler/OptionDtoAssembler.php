<?php

namespace App\Internal\Assembler;

use App\External\Contracts\OptionDtoInterface;
use App\External\Dto\Option;

final class OptionDtoAssembler implements OptionDtoAssemblerInterface
{
    public function create(array|null $data): OptionDtoInterface
    {
        return new Option($data);
    }
}
