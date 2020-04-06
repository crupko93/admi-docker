<?php

namespace App\Http\Controllers\API;

use Auth, DB, Hash, Mail;

use App\{Http\Requests\UserGetRequest, Http\Requests\UserRequest, Notifications\UserPasswordChanged, Role, User};
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;
use phpDocumentor\Reflection\Types\Integer;
use App\Http\Resources\{
    User as UserResource,
    UserCollection
};

use Carbon\Carbon;
use Illuminate\Http\Request;
use Vyuldashev\LaravelOpenApi\Annotations\{Collection, Operation, Parameters, PathItem, RequestBody, Response};
/**
 * @PathItem()
 */
class UserAPIController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:write_administration_section')->only([
            'postIndex',
            'putIndex',
            'deleteIndex',
            'putRole'
        ]);

        $this->middleware('permission:read_administration_section')->only([
            'getIndex'
        ]);
    }

    ////////////////
    // ADMIN ONLY //
    ////////////////
    /**
     * Get user
     *
     * Return a single user or a collection of all users
     *
     */
    public function getIndex(UserGetRequest $request, $user_id = null)
    {
        $user_id = (int)$user_id;

        if (empty($user_id)) {
            // CASE 1 - No ID given, get ALL users
            $users = new User;

            // Filter by role if present
            if ($request->has('role')) {
                switch ($request->role) {
                    case 'admin':
                    case 'moderator':
                    case 'user':
                        $users = $users->role($request->role);
                        break;
                    case 'system':
                        $users = $users->role(\Illuminate\Support\Collection::make(['admin', 'moderator']));
                        break;
                }
            }
            $users = $users->with('roles')->tablePaginate($request);

            return UserCollection::make($users);
        }

        // CASE 2 - ID given, get single user
        $user = User::find($user_id);

        if (empty($user)) {
            return error(trans('user.invalid_user'));
        }

        $user->load('roles');

        return success(['user' => UserResource::make($user)]);
    }

    /**
     * Create user
     *
     * Create a new user
     *
     * @Operation()
     * @RequestBody(factory="PutUserRequestBody")
     * @Response(factory="PutUserResponse")
     * @Response(factory="ErrorResponse")
     *
     */
    public function postIndex(UserRequest $request)
    {
        return DB::try(function () use ($request) {
            $user = new User([
                'username'   => $request['username'],
                'first_name' => $request['first_name'],
                'last_name'  => $request['last_name'],
                'email'      => $request['email'],
                'phone'      => $request['phone'],
                'password'   => Hash::make($request['password']),
            ]);

            if (!$user->save()) {
                return error(trans('user.could_not_create_user'));
            }

            $user->assignRole($request['role']);

            return success([
                'user' => UserResource::make($user)
            ]);
        }, trans('user.could_not_create_user'));
    }

    /**
     * Update user
     *
     * Update a user based on ID
     *
     * @Operation()
     * @RequestBody(factory="PutUserRequestBody")
     * @Response(factory="PutUserResponse")
     * @Response(factory="ErrorResponse")
     *
     */
    public function putIndex(UserRequest $request)
    {
        return DB::try(function () use ($request) {
            if (!$request->id) {
                return error(trans('user.invalid_data_recheck'));
            }

            $user = User::find($request->id);

            if (empty($user)) {
                return error(trans('user.invalid_user'));
            }

            $user->syncRoles($request->role);

            $data = [
                'username'   => $request->username,
                'first_name' => $request->first_name,
                'last_name'  => $request->last_name,
                'email'      => $request->email,
                'phone'      => $request->phone,
                'password'   => Hash::make($request->password),

            ];

            $user->fill($data);

            if (!$user->save()) {
                return error(trans('user.could_not_update_user'));
            }

            return success(['user' => UserResource::make($user)]);
        });
    }

    /**
     * Delete user
     *
     * Delete a user based on ID
     *
     */
    public function deleteIndex(string $user_id)
    {
        dd(gettype($user_id));
        return DB::try(function () use ($user_id) {
            if (empty($user_id)) {
                return error(trans('user.invalid_data_recheck'));
            }

            $user = User::find($user_id);

            if (empty($user)) {
                return error(trans('user.invalid_user'));
            }

            if (!$user->delete()) {
                return error(trans('user.could_not_delete_user'));
            }

            return success();
        });
    }

    /**
     * Update user role
     *
     * Update user role based on user ID
     *
     * @Operation()
     * @RequestBody(factory="UpdateUserRoleRequestBody")
     * @Response(factory="SuccessResponse")
     * @Response(factory="ErrorResponse")
     *
     */
    public function putRole(Request $request)
    {
        return DB::try(function () use ($request) {
            if (!$request->filled('id')) {
                return error(trans('user.invalid_data_recheck'));
            }

            $user = User::find($request->id);

            if (empty($user)) {
                return error(trans('user.invalid_user'));
            }
            if (!$request->filled('role')) {
                return error(trans('user.no_role_provided'));
            }

            if (!$user->syncRoles($request->role)) {
                return error(trans('user.could_not_update_role'));
            }

            return success();
        });
    }

    /**
     * Update user's password
     *
     * Update given user's password (if ID present, admin only) or current user's password (if ID missing)
     *
     * @Operation()
     * @RequestBody(factory="PutPasswordRequestBody")
     * @Response(factory="SuccessResponse")
     *
     */
    public function putPassword(Request $request)
    {
        return DB::try(function () use ($request) {
            if ($request->has('id')) {
                ///////////////////////////////////
                // ADMIN: Change user's password //
                ///////////////////////////////////

                // Ensure password field is sent in form data
                $this->validate($request, ['password' => 'required']);

                $user = User::find($request->id);

                if (empty($user)) {
                    return error(trans('user.invalid_user'));
                }

                // Encrypt password before saving
                $user->password = Hash::make($request->password);

                if (!$user->save()) {
                    return error(trans('user.could_not_update_password'));
                }

                // Send user password via email if desired
                if ($request->has('send_password')) {
                    $user->notify(
                        new UserPasswordChanged($user, $request->password)
                    );
                }
            } else {
                ///////////////////////////////
                // USER: Change own password //
                ///////////////////////////////
                $user = Auth::user();

                $this->validate($request, [
                    'current_password' => 'required',
                    'password'         => sprintf('required|confirmed|min:5')
                ]);

                if (!Hash::check($request->current_password, $user->password)) {
                    return error(trans('user.invalid_password'));
                }

                // Encrypt password before saving
                $user->password = Hash::make($request->password);

                if (!$user->save()) {
                    return error(trans('user.could_not_update_password'));
                }

                // Send user password via email
                $user->notifyPasswordChanged($request->password);
            }

            return success();
        });
    }
}
