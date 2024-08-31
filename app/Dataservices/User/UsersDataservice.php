<?php


namespace App\Dataservices\User;

use App\Http\Requests\UserRequest;
use App\Models\Company;
use App\Models\Frc;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersDataservice
{

    public static function provideEditor(Request $request, User $user): array
    {
        if (!empty($request->old())) $user->fill($request->old());
        return [
            'user' => $user,
            'route' => 'addUser',
            // 'frcs' => Frc::query()->orderBy('name')->get(),
            // 'companies' => Company::query()->orderBy('name')->get(),
        ];
    }

    public static function store(UserRequest $request)
    {
        $user = new User();
        self::saveChanges($request, $user);
    }

    public static function update(UserRequest $request, User $user)
    {
        self::saveChanges($request, $user);
    }

    public static function saveChanges(UserRequest $request, User $user)
    {
        // dd($request);
        $user->fill($request->except(['photo_file']));
        if (!$user->id) $user->password = Hash::make('12345678');


        if ($request->file('img_file')) {
            $file = $request->file('img_file');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            dd($filename);
            $file->storeAs('public/img/avatars', $filename); // Сохраняем файл в директорию 'public/img/avatars'
            $user->photo = $filename;
        }
        
        $is_superuser = $request->has('is_superuser');
        $user->is_superuser = $is_superuser;
        $user->save();
    }

    public static function erase(User $user)
    {
        try {
            $user->delete();
            session()->flash('message', 'Пользователь удален');
        } catch (Error $err) {
            session()->flash('error', 'Не удалось удалить пользователя');
        }
    }

    public static function getNewRolesForUser(User $user):array
    {
        $alreadyAttachedRolesIds = $user->roles->pluck('id')->toArray();
        $roles = Role::query()
            ->whereNotIn('id',$alreadyAttachedRolesIds)
            ->orderBy('name')->get();
        return ['user' => $user,
            'roles' => $roles
        ];
    }

    public static function getNewPermissonsForUser(User $user):array
    {
        $alreadyAttachedPermissions = collect([])->merge($user->permissions);
        foreach($user->roles as $role)
        {
            $alreadyAttachedPermissions = $alreadyAttachedPermissions->merge(collect($role->permissions));
        }

        $alreadyAttachedPermissionsIds =$alreadyAttachedPermissions->pluck('id')->toArray();

        $permissions = Permission::query()->whereNotIn('id',$alreadyAttachedPermissionsIds)
            ->orderBy('name')->get();
        return [
            'user' => $user,
            'permissions' => $permissions,
        ];
    }


    public static function attachPermission(User $user, Permission $permission)
    {
        if (!$user->hasPermissionTo($permission))
        $user->permissions()->attach($permission);
    }

    public static function detachPermission(User $user, Permission $permission)
    {
        $user->permissions()->detach($permission);
    }

}
