<?php

namespace App\Http\Controllers\User;

use App\Dataservices\User\RoleDataservice;
use App\Dataservices\User\UsersDataservice;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Mail\UserCreated;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Error;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request)
    {
        return view('user.users', ['users' => User::query()->orderBy('name')->get(), 'filter' => '']);
    }

    public function add(Request $request): View
    {
        $user = new User();
        if (!empty($request->old())) $user->fill($request->old());
        return view('user.user-edit', UsersDataservice::provideEditor($request, $user));
    }

    public function store(UserRequest $request): RedirectResponse
    {
        try {
            UsersDataservice::store($request);
            $user = User::query()->where('email','=', $request->post('email'))->first();
            // if ($user->role != "user")
            //     Mail::to($request->user())->send(new UserCreated($user));
            session()->flash('message', 'Добавлен новый пользователь');
        } catch (Error $err) {
            session()->flash('error', 'Не удалось добавить нового пользователя');
        }
        return redirect()->route('users');
    }

    public function edit(Request $request, User $user)
    {
        if (!empty($request->old())) $user->fill($request->old());
        return view('user.user-edit', UsersDataservice::provideEditor($request, $user));
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        try {
            UsersDataservice::update($request, $user);
            session()->flash('message', 'Информация о пользователе обновлена');
        } catch (Error $err) {
            session()->flash('error', 'Не удалось обновить информацию о пользователе');
        }
        return redirect()->route('users');
    }

    public function delete(User $user)
    {
        if (User::all()->count() < 2) {
            return redirect()->back()->with('error', 'Невозможно удалить последнего пользователя');
        }
        $user->delete();
        return redirect()->route('users');
    }

    public function userSummary(User $user)
    {
        return view('user.user-summary',
            ['user' => $user,
            ]);

    }

    public function setTempPassword(Request $request, User $user)
    {
        if (Auth::user()->id == $user->id) {
            return redirect()->back()->with('error', 'Невозможно использовать данный метод для установки пароля самому себе');
        }
        if ($request->isMethod('POST')) {
            $tempPassword = $request->post('tempPassword');
            $user->password = Hash::make($tempPassword);
            $user->password_changed_at = null;
            $user->save();
            return redirect()->route('users')->with('message', 'Временный пароль установлен');
        } else {
            return view('user.user-setTempPassword', ['user' => $user]);
        }
    }

    public function addRole(User $user): View
    {
        return view('user.user-add-role',
            UsersDataservice::getNewRolesForUser($user));
    }

    public function attachRole(Request $request, User $user): RedirectResponse
    {
        try {
            $role = Role::findOrFail($request->post('role_id'));
            RoleDataservice::attachUserToRole($role, $user);
            session()->flash('message', 'Пользователю назначена роль');
        } catch(Error $err) {
            session()->flash('message', 'Не удалось назначить роль пользователю');
        }
        return redirect()->route('userSummary', ['user' => $user, 'page' => 'permissions']);
    }

    public function detachRole(User $user, Role $role):RedirectResponse
    {
        try {
            RoleDataservice::detachUserFromRole($role, $user);
            session()->flash('message', 'Роль отозвана у пользователя');
        } catch(Error $err) {
            session()->flash('message', 'Не удалось отозвать роль у пользователя');
        }
        return redirect()->route('userSummary', ['user' => $user, 'page' => 'permissions']);
    }

    public function addPermission(User $user): View
    {
        return view('user.user-add-permission',
            UsersDataservice::getNewPermissonsForUser($user));
    }

    public function attachPermission(Request $request, User $user): RedirectResponse
    {
        try {
            $permission = Permission::findOrFail($request->post('permission_id'));
            UsersDataservice::attachPermission($user, $permission);
            session()->flash('message', 'Пользователю выдана привилегия');
        } catch(Error $err) {
            session()->flash('message', 'Не удалось выдать привилегию пользователю');
        }
        return redirect()->route('userSummary', ['user' => $user, 'page' => 'permissions']);
    }

    public function detachPermission(User $user, Permission $permission):RedirectResponse
    {
        try {
            UsersDataservice::detachPermission($user, $permission);
            session()->flash('message', 'Разрешение отозвано у пользователя');
        } catch(Error $err) {
            session()->flash('message', 'Не удалось отозвать разрешение у пользователя');
        }
        return redirect()->route('userSummary', ['user' => $user, 'page' => 'permissions']);
    }

}
