<?php

namespace Tests\Infra\Factories;

use Tests\Infra\Models\ItemDiscount;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ItemDiscount>
 */
final class ItemDiscountFactory extends Factory
{
    protected $model = ItemDiscount::class;

    public function definition(): array
    {
        return [
            'name' => fake()
                ->domainName,
            'price' => random_int(200, 700),
            'quantity' => random_int(10, 100),
        ];
    }
}
