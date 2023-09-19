<?php

namespace App\Internal\Assembler;

use App\External\Contracts\ExtraDtoInterface;

interface ExtraDtoAssemblerInterface
{
    /**
     * @param ExtraDtoInterface|array<mixed>|null $data
     */
    public function create(ExtraDtoInterface|array|null $data): ExtraDtoInterface;
}
