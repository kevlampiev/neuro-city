<?php

namespace Database\Seeders;

use App\Models\AgreementType;
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
        $this->call([
            UserSeeder::class,
            CompanySeeder::class,
            RoleSeeder::class,
            AgreementTypeSeeder::class,
            AgreementSeeder::class,
        ]);

        $this->call(ProjectSeeder::class);
 
        
    }

}
