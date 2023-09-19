<?php

namespace App\Internal\Service;

interface StateServiceInterface
{
    /**
     * @param string[] $uuids
     * @param callable(): array<string, string> $value
     */
    public function multiFork(array $uuids, callable $value): void;

    public function get(string $uuid): ?string;

    public function drop(string $uuid): void;
}
