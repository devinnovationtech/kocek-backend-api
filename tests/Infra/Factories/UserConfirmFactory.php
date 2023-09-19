<?php

namespace Tests\Infra\Factories;

use Tests\Infra\Models\UserConfirm;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserConfirm>
 */
final class UserConfirmFactory extends Factory
{
    protected $model = UserConfirm::class;

    public function definition(): array
    {
        return [
            'name' => fake()
                ->name,
            'email' => fake()
                ->unique()
                ->safeEmail,
        ];
    }
}
