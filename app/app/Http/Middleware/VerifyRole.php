<?php

namespace App\Http\Middleware;

use Closure, Role;

class VerifyRole
{
    /**
     * Handle an incoming request
     * @param  Request $request
     * @param  Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $roles = array_slice(func_get_args(), 2);

        $forbidden = true;

        foreach ($roles as $role) {
            if (Role::is($role)) {
                $forbidden = false;
                break;
            }
        }

        abort_if($forbidden, 403, 'Forbidden...');

        return $next($request);
    }
}
