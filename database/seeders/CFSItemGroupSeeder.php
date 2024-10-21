<?php

namespace Database\Seeders;

use App\Models\CFSItemGroup;
use App\Models\CFSItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Project;

class CFSItemGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        CFSItemGroup::factory()
            ->count(10)
            ->has(CFSItem::factory()->count(5)) // Для каждой группы создаем 5 CFSItem
            ->create();
    }
}
