<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use App\Models\Role;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        User::factory()
            ->count(20)
            ->create();

        Company::factory()
            ->count(120)
            ->create();

        DB::table('roles')->insert([
            'name' => "Юрисконсульт",
            'slug' => "urist1",
        ]);
        DB::table('roles')->insert([
            'name' => "Экономист",
            'slug' => "econom1",
        ]);
        DB::table('roles')->insert([
            'name' => "Программист",
            'slug' => "programmer1",
        ]);

    }

}
