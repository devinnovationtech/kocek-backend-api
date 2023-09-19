<?php

namespace App\Internal\Service;

use Throwable;

/**
 * @internal
 */
final class JsonService implements JsonServiceInterface
{
    public function encode(?array $data): ?string
    {
        try {
            return $data === null ? null : json_encode($data, JSON_THROW_ON_ERROR);
        } catch (Throwable) {
            return null;
        }
    }
}
