<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;

use Vyuldashev\LaravelOpenApi\Annotations\{Operation, Parameters, PathItem, RequestBody, Response};

/**
 * @PathItem()
 */
class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {}

    /**
     * Authenticate user
     *
     * Get a JWT via given credentials.
     *
     * @Operation()
     * @RequestBody(factory="LoginRequestBody")
     * @Response(factory="PutUserResponse")
     * @Response(factory="ErrorResponse")
     *
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        $field = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $field     => $request->email,
            'password' => $request->password
        ];

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['message' => trans('auth.failed')], 401);
        }

        $user = $request->user();

        $user->load('roles');

        return response()->json(compact('token', 'user'));
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        $token = auth()->refresh();

        return response()->json(compact('token'));
    }

    /**
     * Log the user out.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => trans('auth.log_out')]);
    }
}
