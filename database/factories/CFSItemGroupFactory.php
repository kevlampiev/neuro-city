<?php

namespace Database\Factories;

use App\Models\CFSItemGroup;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CFSItemGroupFactory extends Factory
{
    protected $model = CFSItemGroup::class;

    public function definition()
    {
        
        return [
            // Генерация случайного пользователя
            'created_by' => User::factory(),
            'cfs_section' => ['operations', 'finance', 'investment'] [rand(0,2)],
            'direction' => ['inflow', 'outflow'] [rand(0,1)],
            'name' => $this->faker->word,
            // 'description' => $this->faker->sentence,
        ];
    }
}