<?php

namespace App\Helpers;

use Auth;

class RoleHelper
{
    /**
     * Check if the authenticated user's role matches the given role
     * @param  string  $role role to verify
     * @return boolean
     */
    public static function is($role)
    {
        return Auth::user()->role === $role;
    }

    /**
     * Check if the authenticated user's role is `admin`
     * @return boolean
     */
    public static function admin()
    {
        return self::is('admin');
    }

    /**
     * Check if the authenticated user's role is `moderator`
     * @return boolean
     */
    public static function moderator()
    {
        return self::is('moderator');
    }

    /**
     * Check if the authenticated user's role is `user`
     * @return boolean
     */
    public static function user()
    {
        return self::is('user');
    }
}
