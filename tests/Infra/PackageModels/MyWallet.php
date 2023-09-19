<?php

namespace Tests\Infra\PackageModels;

final class MyWallet extends \App\Models\Wallet
{
    public function helloWorld(): string
    {
        return 'hello world';
    }
}
