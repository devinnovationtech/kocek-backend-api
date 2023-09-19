<?php

namespace Tests\Infra\Factories;

use Tests\Infra\Models\ItemMaxTax;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ItemMaxTax>
 */
final class ItemMaxTaxFactory extends Factory
{
    protected $model = ItemMaxTax::class;

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
