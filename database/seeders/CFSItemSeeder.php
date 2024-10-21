<?php

namespace Database\Seeders;

use App\Models\CFSItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Project;

class CFSItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CFSItem::factory()
            ->count(30)
            ->create();
    }
}
