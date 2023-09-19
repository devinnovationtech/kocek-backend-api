<?php

namespace App\External\Contracts;

interface OptionDtoInterface
{
    /**
     * @return null|array<mixed>
     */
    public function getMeta(): ?array;

    public function isConfirmed(): bool;

    public function getUuid(): ?string;
}
