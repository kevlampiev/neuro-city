<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $urist = new Role();
        $urist->name = "Юрисконсульт";
        $urist->slug = 'urist';
        $urist->save();

        $urist->permissions()->attach(Permission::where('slug','s-counterparty')->first());
        $urist->permissions()->attach(Permission::where('slug','e-counterparty')->first());

        $economist = new Role();
        $economist->name = "Экономист";
        $economist->slug = 'econom';
        $economist->save();
        $economist->permissions()->attach(Permission::where('slug','s-counterparty')->first());
        $economist->permissions()->attach(Permission::where('slug','e-counterparty')->first());

        $programmer = new Role();
        $programmer->name = "Программист";
        $programmer->slug = 'programmer';
        $programmer->save();

        $users = User::query()->where('is_superuser','=',false)->inRandomOrder()->limit(3)->get();
        $user1 = $users[0];
        $user1->roles()->attach($urist);
        $user1->password_changed_at=now();
        $user1->save();

        $user2 = $users[1];
        $user2->roles()->attach($economist);
        $user2->password_changed_at=now();
        $user2->save();

        $user3 = $users[2];
        $user3->roles()->attach($programmer);
        $user3->password_changed_at=now();
        $user3->save();

    }
}
