<?php

namespace Database\Seeders;

use App\Models\AgreementType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class AgreementTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AgreementType::factory()
            ->count(10)
            ->create();
       
    }
}
