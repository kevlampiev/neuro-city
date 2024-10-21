<?php

namespace Database\Factories;

use App\Models\CFSItem;
use App\Models\CFSItemGroup;
use App\Models\PlItem;
use App\Models\PlItemGroup;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlItemFactory extends Factory
{
    protected $model = PlItem::class;

    public function definition()
    {
        return [
            // Генерация случайного пользователя
            'created_by' => User::factory(),
            // Генерация группы через фабрику
            'pl_item_group_id' => PlItemGroup::factory(),
            // Добавьте необходимые поля для CFSItem
            
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
        ];
    }
}
