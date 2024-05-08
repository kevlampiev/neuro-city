<?php 

namespace App\Traits;

use App\Models\Role;
use App\Models\Permission;

trait HasRolesAndPermissions
{
    /**
     * @return mixed
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class,'users_roles');
    }

    /**
     * @return mixed
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class,'users_permissions');
    }

    /**
     * @param mixed ...$roles
     * @return bool
     */
    public function hasRole(... $roles ) {
        if ($this->role == "admin") return true; // добавил от себя к тексту https://laravel.demiart.ru/guide-to-roles-and-permissions/ 
        foreach ($roles as $role) {
            if ($this->roles->contains('slug', $role)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        if ($this->is_superuser == true) return true; // добавил от себя к тексту https://laravel.demiart.ru/guide-to-roles-and-permissions/ 
        return (bool) $this->permissions->where('slug', $permission)->count();
    }

    /**
     * @param $permission
     * @return bool
     */
    public function hasPermissionThroughRole($permission)
    {
        if ($this->is_superuser == true) return true; // добавил от себя к тексту https://laravel.demiart.ru/guide-to-roles-and-permissions/ 
        if (gettype($permission)=="string") {
            $permission = Permission::where('slug','=',$permission)->first();
            if (!$permission) return false;
        }
        foreach ($permission->roles as $role){
            if($this->roles->contains($role)) {
                return true;
            }
        }
        return false;
    }


    /**
     * @param $permission
     * @return bool
     */
    public function hasPermissionTo($permission)
    {
        if ($this->is_superuser == true) return true; // добавил от себя к тексту https://laravel.demiart.ru/guide-to-roles-and-permissions/ 
            if (gettype($permission)=="string") {
                $permission = Permission::where('slug','=',$permission)->first();
                if (!$permission) return false;
            }
        return $this->hasPermissionThroughRole($permission) || $this->hasPermission($permission->slug);
    }

    /**
     * @param array $permissions
     * @return mixed
     */
    public function getAllPermissions(array $permissions)
    {
        if ($this->is_superuser == true) return Permission::all(); // добавил от себя к тексту https://laravel.demiart.ru/guide-to-roles-and-permissions/ 
        return Permission::whereIn('slug',$permissions)->get();
    }

    /**
     * @param mixed ...$permissions
     * @return $this
     */
    public function givePermissionsTo(... $permissions)
    {
        $permissions = $this->getAllPermissions($permissions);
        if($permissions === null) {
            return $this;
        }
        $this->permissions()->saveMany($permissions);
        return $this;
    }

    /**
     * @param mixed ...$permissions
     * @return $this
     */
    public function deletePermissions(... $permissions )
    {
        $permissions = $this->getAllPermissions($permissions);
        $this->permissions()->detach($permissions);
        return $this;
    }

    /**
     * @param mixed ...$permissions
     * @return HasRolesAndPermissions
     */
    public function refreshPermissions(... $permissions )
    {
        $this->permissions()->detach();
        return $this->givePermissionsTo($permissions);
    }

}