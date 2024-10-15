<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\DroidType;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class DroidTypeFactory extends Factory
{
    protected $model = DroidType::class;

    public function definition(): array
    {
        $user = User::where('is_superuser', true)->first();
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->sentence(), // Исправлено описание
            'created_by' => $user->id,
            'created_at' => now(),
        ];
    }
}
