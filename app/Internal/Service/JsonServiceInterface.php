<?php

namespace App\Internal\Service;

interface JsonServiceInterface
{
    /**
     * @param array<mixed>|null $data
     */
    public function encode(?array $data): ?string;
}
