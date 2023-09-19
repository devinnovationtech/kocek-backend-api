<?php

namespace Tests\Infra\Factories;

use Tests\Infra\Models\ItemWallet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ItemWallet>
 */
final class ItemWalletFactory extends Factory
{
    protected $model = ItemWallet::class;

    public function definition(): array
    {
        return [
            'name' => fake()
                ->domainName,
            'price' => random_int(1, 100),
            'quantity' => random_int(0, 10),
        ];
    }
}
