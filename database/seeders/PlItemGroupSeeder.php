<?php

namespace Database\Seeders;

use App\Models\CFSItemGroup;
use App\Models\CFSItem;
use App\Models\PlItem;
use App\Models\PlItemGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Project;

class PlItemGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        PlItemGroup::factory()
            ->count(10)
            ->has(PlItem::factory()->count(5)) // Для каждой группы создаем 5 CFSItem
            ->create();
    }
}
