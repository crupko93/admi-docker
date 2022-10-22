<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class CheckPermission
{
    /**
     * Handle an incoming request
     * @param  Request $request
     * @param  Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$permissions)
    {
        $forbidden = true;

        foreach ($permissions as $permission) {
            if ($request->user()->hasPermissionTo($permission)) {
                $forbidden = false;
                break;
            }
        }

        abort_if($forbidden, 403, 'You don\'t have the required permissions ');

        return $next($request);
    }
}
