<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use App\User;
use Vyuldashev\LaravelOpenApi\Annotations\Operation;
use Vyuldashev\LaravelOpenApi\Annotations\Parameters;
use Vyuldashev\LaravelOpenApi\Annotations\PathItem;
use Vyuldashev\LaravelOpenApi\Annotations\RequestBody;
use Vyuldashev\LaravelOpenApi\Annotations\Response;


/**
 * @PathItem()
 */
class ProfileAPIController extends Controller
{
    /**
     * Update user
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
            'name' => $request->input('name'),
            'email' => $request->input('email')
        ]);

        if ($request->input('password')) {
            $user->password = bcrypt($request->input('password'));
        }

        $user->save();

        return success(['user' => $user]);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIndex()
    {
        $user = auth()->user();

        return response()->json(compact('user'));
    }
}
