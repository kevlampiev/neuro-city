<?php

namespace App\Http\Middleware;

use App\Models\Permission;
use Closure;
use Illuminate\Http\Request;

use function PHPSTORM_META\type;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        if ($request->is('storage/*')) {
            return $next($request);
        }
        if (gettype($permission)=="string") {
                $permission = Permission::where('slug','=',$permission)->first();
                if (!$permission) return false;
            }
        if(!auth()->user()->hasPermissionTo($permission)) 
        {
                abort(404);
        } 
        return $next($request);
    }
}
