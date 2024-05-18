<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()
            ->count(30)
            ->create();
        $superusers = User::query()->inRandomOrder()->limit(3)->get();
        foreach($superusers as $user)
        {
            $user->is_superuser = true;
            $user->password_changed_at = now();
            $user->save();
        }

        $oldSuperuser = User::where('is_superuser', true)->inRandomOrder()->first();
        $oldSuperuser->password_changed_at = null;
        $oldSuperuser->save();


        $simpleusers = User::where('is_superuser', false)->inRandomOrder()->limit(3)->get();
        foreach($simpleusers as $user)
        {
             $user->password_changed_at = now();
            $user->save();
        }
    }
}
