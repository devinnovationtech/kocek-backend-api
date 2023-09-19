<?php

namespace App\Internal\Service;

interface TranslatorServiceInterface
{
    public function get(string $key): string;
}
