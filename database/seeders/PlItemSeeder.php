<?php

namespace Database\Seeders;

use App\Models\CFSItem;
use App\Models\PlItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Project;

class PlItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PlItem::factory()
            ->count(30)
            ->create();
    }
}
