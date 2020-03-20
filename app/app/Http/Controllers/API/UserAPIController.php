<?php

namespace App\Http\Controllers\API;

use Auth, DB, Hash, Mail, Role;

use App\{Http\Requests\UserGetRequest, Http\Requests\UserRequest, Notifications\UserPasswordChanged, User};
use App\Http\Controllers\Controller;
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
        $this->middleware('role:admin')->only([
            'getIndex',
            'postIndex',
            'putIndex',
            'deleteIndex',
            'putRole'
        ]);

        $this->middleware('role:user')->only([
            'deleteOwnAccount'
        ]);
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
                if (!Role::admin()) {
                    return error('Forbidden!');
                }

                // Ensure password field is sent in form data
                $this->validate($request, ['password' => 'required']);

                $user = User::find($request->id);

                if (empty($user)) {
                    return error('Invalid user!');
                }

                // Encrypt password before saving
                $user->password = Hash::make($request->password);

                if (!$user->save()) {
                    return error('Could not update password...');
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
                    return error('The current password is invalid!');
                }

                // Encrypt password before saving
                $user->password = Hash::make($request->password);

                if (!$user->save()) {
                    return error('Could not update password...');
                }

                // Send user password via email
                $user->notifyPasswordChanged($request->password);
            }

            return success();
        });
    }

    ////////////////
    // ADMIN ONLY //
    ////////////////
    /**
     * Get user
     *
     * Return a single user or a collection of all users
     *
     * @Operation()
     * @param UserGetRequest $request
     * @param int $user_id User ID
     * @Response(factory="PutUserResponse")
     * @Collection(factory="ListUsersResponse")
     * @Response(factory="ErrorResponse")
     *
     */
    public function getIndex(UserGetRequest $request, int $user_id = null)
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
                        $users = $users->where('role', $request->role);
                        break;
                    case 'system':
                        $users = $users->whereIn('role', ['admin', 'moderator']);
                        break;
                }
            }
            $users = $users->tablePaginate($request);

            return UserCollection::make($users);
        }

        // CASE 2 - ID given, get single user
        $user = User::find($user_id);

        if (empty($user)) {
            return error('Invalid user!');
        }

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
                'role'       => $request['role'],
                'password'   => Hash::make($request['password']),
            ]);

            if (!$user->save()) {
                return error('Could not create user...');
            }

            return success([
                'user' => UserResource::make($user)
            ]);
        }, 'Could not create user...');
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
                return error('Invalid data! Please recheck and try again...');
            }

            $user = User::find($request->id);

            if (empty($user)) {
                return error('Invalid user!');
            }

            $data = [
                'username'   => $request->username,
                'first_name' => $request->first_name,
                'last_name'  => $request->last_name,
                'email'      => $request->email,
                'phone'      => $request->phone,
                'role'       => $request->role,
                'password'   => Hash::make($request->password),

            ];

            $user->fill($data);

            if (!$user->save()) {
                return error('Could not update user...');
            }

            return success(['user' => UserResource::make($user)]);
        });
    }

    /**
     * Delete user
     *
     * Delete a user based on ID
     *
     * @Operation()
     * @param int $user_id User ID
     * @Response(factory="SuccessResponse")
     * @Response(factory="ErrorResponse")
     *
     */
    public function deleteIndex(int $user_id)
    {
        return DB::try(function () use ($user_id) {
            $user_id = (int)$user_id;

            if (empty($user_id)) {
                return error('Invalid data! Please recheck and try again...');
            }

            $user = User::find($user_id);

            if (empty($user)) {
                return error('Invalid user!');
            }

            if (!$user->delete()) {
                return error('Could not delete user...');
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
            $user = User::find($request->id);

            if (empty($user)) {
                return error('Invalid user!');
            }

            $user->role = $request->role;

            if (!$user->save()) {
                return error('Could not update role...');
            }

            return success();
        });
    }
}