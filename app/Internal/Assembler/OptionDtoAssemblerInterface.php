<?php

namespace App\Internal\Assembler;

use App\External\Contracts\OptionDtoInterface;

interface OptionDtoAssemblerInterface
{
    /**
     * @param null|array<mixed> $data
     */
    public function create(array|null $data): OptionDtoInterface;
}
