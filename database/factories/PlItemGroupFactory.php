<?php

namespace Database\Factories;

use App\Models\CFSItemGroup;
use App\Models\PlItemGroup;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlItemGroupFactory extends Factory
{
    protected $model = PlItemGroup::class;

    public function definition()
    {
        
        return [
            // Генерация случайного пользователя
            'created_by' => User::factory(),
            'pl_section' => ['sales', 'cogs', 'indirect_costs', 'DA', 'interests','tax' ] [rand(0,5)],
            'direction' => ['inflow', 'outflow'] [rand(0,1)],
            'name' => $this->faker->word,
            'weight' => rand(0,10),
            // 'description' => $this->faker->sentence,
        ];
    }
}