<?php

namespace App\Http\Controllers\User;

use App\Dataservices\User\RoleDataservice;
use App\Http\Controllers\Controller;
use App\Http\Requests\InsuranceRequest;
use App\Http\Requests\RoleRequest;
use App\Models\Insurance;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\Vehicle;
use Error;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function index(Request $request):View
    {
        return view('role.roles', RoleDataservice::index($request));
    }

    public function create(Request $request): View
    {
        $role = RoleDataservice::create($request);
        return view('role.role-edit',
            RoleDataservice::provideRoleEditor($role));
    }

    public function store(RoleRequest $request): RedirectResponse
    {
        RoleDataservice::store($request);
        return redirect()->to(route('roles', []));
    }


    public function edit(Request $request, Role $role):View
    {
        if (!empty($request->old())) $role->fill($request->old());
        return view('role.role-edit',
            RoleDataservice::provideRoleEditor($role));
    }

    public function update(RoleRequest $request, Role $role): RedirectResponse
    {
        RoleDataservice::update($request, $role);
        return redirect()->to(route('roles'));
    }

    public function delete(Role $role): RedirectResponse
    {
        RoleDataservice::delete($role);
        return redirect()->to(route('roles'));
    }

    public function roleSummary(Role $role):View
    {
        return view('role.role-summary', ['role' => $role]);
    }

    public function addUser(Role $role): View
    {
        return view('role.role-add-user',
            RoleDataservice::getNewUsersForRole($role));
    }

    public function storeUser(Request $request, Role $role):RedirectResponse
    {
        try {
            $user = User::findOrFail($request->post('user_id'));
            RoleDataservice::attachUserToRole($role, $user);
            session()->flash('message', 'Пользователю назначена роль');
        } catch(Error $err) {
            session()->flash('message', 'Не удалось назначить роль пользователю');
        }
        return redirect()->route('role.roleSummary', ['role' => $role, 'page' => 'users']);
    }

    public function detachUser(Role $role, User $user):RedirectResponse
    {
        try {
            RoleDataservice::detachUserFromRole($role, $user);
            session()->flash('message', 'Роль отозвана у пользователя');
        } catch(Error $err) {
            session()->flash('message', 'Не удалось отозвать роль у пользователя');
        }
        return redirect()->route('roleSummary', ['role' => $role, 'page' => 'users']);
    }

    public function addPermission(Role $role): View
    {
        return view('role.role-add-permission',
            RoleDataservice::getNewPermissonsForRole($role));
    }

    public function storePermission(Request $request, Role $role):RedirectResponse
    {
        try {
            $permission = Permission::findOrFail($request->post('permission_id'));
            RoleDataservice::attachPermission($role, $permission);
            session()->flash('message', 'К роли добавлено новое разрешение');
        } catch(Error $err) {
            session()->flash('message', 'Не удалось добавить новое разрешение к роли');
        }
        return redirect()->route('roleSummary', ['role' => $role, 'page' => 'permissions']);
    }

    public function detachPermission(Role $role, Permission $permission):RedirectResponse
    {
        try {
            RoleDataservice::detachPermission($role, $permission);
            session()->flash('message', 'Разрещение удалено из роли');
        } catch(Error $err) {
            session()->flash('message', 'Не удалось удалить разрешение у роли');
        }
        return redirect()->route('roleSummary', ['role' => $role, 'page' => 'permissions']);
    }




}
