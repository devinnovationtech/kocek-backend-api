<?php

namespace App\Internal\Service;

use Illuminate\Database\ConnectionInterface;

interface ConnectionServiceInterface
{
    public function get(): ConnectionInterface;
}
