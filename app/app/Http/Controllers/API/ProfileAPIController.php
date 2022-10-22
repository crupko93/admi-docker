<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\User as UserResource;
use Illuminate\Http\Request;
use App\User;
use Vyuldashev\LaravelOpenApi\Annotations\{Operation, Parameters, PathItem, RequestBody, Response};


/**
 * @PathItem()
 */
class ProfileAPIController extends Controller
{

    /**
     * Get user
     *
     * Get the authenticated user's profile
     *
     * @Operation()
     * @Response(factory="PutUserResponse")
     * @Response(factory="ErrorResponse")
     *
     */
    public function getIndex()
    {
        $user = auth()->user();

        return success(['user' => UserResource::make($user->load('roles'))]);
    }

    /**
     * Update authenticated user
     *
     * Update authenticated user's profile and return the full user object
     *
     * @Operation()
     * @RequestBody(factory="PutUserRequestBody")
     * @Response(factory="PutUserResponse")
     * @Response(factory="ErrorResponse")
     *
     */
    public function putIndex(UserRequest $request)
    {
        $user = $request->user();
        $user->fill([
            'username'   => $request->input('username'),
            'first_name' => $request->input('first_name'),
            'last_name'  => $request->input('last_name'),
            'email'      => $request->input('email'),
            'phone'      => $request->input('phone'),
            'role'       => $request->input('role')

        ]);

        if ($request->input('password')) {
            $user->password = bcrypt($request->input('password'));
        }


        $user->save();

        return success(['user' => UserResource::make($user->load('roles'))]);
    }

    /**
     * Delete own user account
     *
     */
    // TODO check if working
    public function deleteOwnAccount()
    {
        return DB::try(function () {
            $user = Auth::user();

            if (empty($user)) {
                return error(trans('profile.invalid_user'));
            }

            if (!$user->delete()) {
                return error(trans('profile.could_not_delete_user'));
            }

            return success();
        });
    }
}
