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
        $urist->permissions()->attach(Permission::where('slug','s-agreements')->first());
        $urist->permissions()->attach(Permission::where('slug','e-agreements')->first());

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
        $programmer->permissions()->attach(Permission::where('slug','s-projects')->first());
        $programmer->permissions()->attach(Permission::where('slug','e-projects')->first());
        $programmer->permissions()->attach(Permission::where('slug','s-droid_types')->first());
        $programmer->permissions()->attach(Permission::where('slug','e-droid_types')->first());
        $programmer->permissions()->attach(Permission::where('slug','s-ref_books')->first());
        $programmer->permissions()->attach(Permission::where('slug','e-ref_books')->first());

        $users = User::query()->where('is_superuser','=',false)->inRandomOrder()->limit(4)->get();
        $user1 = $users[0];
        $user1->roles()->attach($urist);
        $user1->password_changed_at=now();
        $user1->save();

        $user2 = $users[1];
        $user2->roles()->attach($urist);
        $user2->password_changed_at=null;
        $user2->save();


        $user3 = $users[2];
        $user3->roles()->attach($economist);
        $user3->password_changed_at=now();
        $user3->save();

        $user4 = $users[3];
        $user4->roles()->attach($programmer);
        $user4->password_changed_at=now();
        $user4->save();

    }
}
