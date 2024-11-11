<?php

namespace Database\Seeders;

use App\Models\Payment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\Faker;

class PaymentSeeder extends Seeder
{

    
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Payment::factory()
            ->count(50)
            ->create();
    }
}
