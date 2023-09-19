<?php

namespace App\Internal\Assembler;

use App\External\Contracts\ExtraDtoInterface;
use App\External\Dto\Extra;

final class ExtraDtoAssembler implements ExtraDtoAssemblerInterface
{
    public function __construct(
        private readonly OptionDtoAssemblerInterface $optionDtoAssembler
    ) {
    }

    public function create(ExtraDtoInterface|array|null $data): ExtraDtoInterface
    {
        if ($data instanceof ExtraDtoInterface) {
            return $data;
        }

        $option = $this->optionDtoAssembler->create($data);

        return new Extra($option, $option, null);
    }
}
