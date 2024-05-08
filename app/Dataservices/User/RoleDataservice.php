<?php


namespace App\DataServices\User;


use App\Http\Requests\InsuranceRequest;
use App\Http\Requests\RoleRequest;
use App\Models\Insurance;
use App\Models\InsuranceCompany;
use App\Models\InsuranceType;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Error;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleDataservice
{
    static public function index(Request $request):array
    {
        return [
            'roles' => Role::query()->orderBy('name')->get(),
            ];
    }

    public static function provideRoleEditor(Role $role): array
    {
        return [
            'role' => $role,
        ];
    }

    public static function create(Request $request): Role
    {
        $role = new Role();
        if (!empty($request->old())) $role->fill($request->old());
        return $role;
    }

//    public static function edit(Request $request, Insurance $insurance)
//    {
//        if (!empty($request->old())) $insurance->fill($request->old());
//    }
//
//
//    public static function saveChanges(InsuranceRequest $request, Insurance $insurance)
//    {
//        $insurance->fill($request->except(['policy_file']));
//        if ($insurance->id) $insurance->updated_at = now();
//        else $insurance->created_at = now();
//        if ($request->file('policy_file')) {
//            $file_path = $request->file('policy_file')->store(config('paths.insurances.put', '/public/insurances'));
//            $insurance->policy_file = basename($file_path);
//        }
//        $insurance->save();
//    }
//
    public static function store(Request $request)
    {
        try {
            $role = new Role();
            $role->fill($request->all());
            $role->save();
            session()->flash('message', 'Добавлена новая роль');
        } catch (Error $err) {
            session()->flash('error', 'Не удалось добавить роль');
        }

    }

    public static function update(Request $request, Role $role)
    {
        try {
            $role->fill($request->all());
            $role->save();
            session()->flash('message', 'Данные роли обновлены');
        } catch (Error $err) {
            session()->flash('error', 'Не удалось обновить роль');
        }
    }

    public static function delete(Role $role)
    {
        try {
            $role->delete();
            session()->flash('message', 'Роль удалена');
        } catch (Error $err) {
            session()->flash('error', 'Не удалось удалить роль');
        }
    }

    public static function getNewUsersForRole(Role $role):array
    {
        $alreadyAttachedUserIds = $role->users->pluck('id')->toArray();
        $users = User::query()
            ->whereNotIn('id',$alreadyAttachedUserIds)
            ->orderBy('name')->get();
        return ['role' => $role,
            'users' => $users
        ];
    }

    public static function attachUserToRole(Role $role, User $user)
    {
        if(!$user->hasRole($role)) {
            $user->roles()->attach($role);
        }
    }

    public static function detachUserFromRole(Role $role, User $user)
    {
        $user->roles()->detach($role);
    }

    public static function getNewPermissonsForRole(Role $role):array
    {
        $alreadyAttachedPermissions = $role->permissions()->pluck('id')->toArray();
        $permissions = Permission::query()->whereNotIn('id',$alreadyAttachedPermissions)
        ->orderBy('name')->get();
        return [
            'role' => $role,
            'permissions' => $permissions,
        ];
    }


    public static function attachPermission(Role $role, Permission $permission)
    {
        $role->permissions()->attach($permission);
    }

    public static function detachPermission(Role $role, Permission $permission)
    {
        $role->permissions()->detach($permission);
    }

}
