<?php

namespace Tests\Infra\Factories;

use Tests\Infra\Models\ItemMeta;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ItemMeta>
 */
final class ItemMetaFactory extends Factory
{
    protected $model = ItemMeta::class;

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
