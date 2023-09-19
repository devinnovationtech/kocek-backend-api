<?php

namespace Tests;

use App\WalletConfigure;
use Illuminate\Support\ServiceProvider;

final class TestServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (WalletConfigure::isRunsMigrations()) {
            $this->loadMigrationsFrom([dirname(__DIR__) . '/database/migrations']);
        }
        $this->loadMigrationsFrom([dirname(__DIR__) . '/tests/migrations']);
    }
}
