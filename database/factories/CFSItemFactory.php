<?php

namespace Database\Factories;

use App\Models\CFSItem;
use App\Models\CFSItemGroup;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CFSItemFactory extends Factory
{
    protected $model = CFSItem::class;

    public function definition()
    {
        return [
            // Генерация случайного пользователя
            'created_by' => User::factory(),
            // Генерация группы через фабрику
            'group_id' => CFSItemGroup::factory(),
            // Добавьте необходимые поля для CFSItem
            
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
        ];
    }
}
