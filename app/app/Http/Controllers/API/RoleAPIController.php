<?php

namespace App\Http\Controllers\API;

use App\{Http\Requests\RoleRequest, Role};
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\{Role as RoleResource, RoleCollection};
use DB;
use Illuminate\Contracts\Routing\ResponseFactory;
//use Symfony\Component\HttpFoundation\Response;
use Vyuldashev\LaravelOpenApi\Annotations\{Operation, Parameters, PathItem, RequestBody, Response};


/**
 * @PathItem()
 */
class RoleAPIController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:write_administration_section');
    }

    /**
     * Get role
     *
     * Get specific role or return list of roles
     *
     * @Operation()
     *
     * @Response(factory="SuccessResponse")
     * @Response(factory="ErrorResponse")
     *
     */
    public function getIndex(Request $request, $roleId = null)
    {
        if (!$roleId) {
            return RoleCollection::make(Role::select('id', 'name')->with('permissions')->tablePaginate($request));
        }

        $role = Role::with('permissions')->find($roleId);

        if (!$role) {
            return error(trans('role.invalid_role'));
        }

        return success(['role' => RoleResource::make($role)]);
    }

    /**
     * Update role
     *
     *
     * @Operation()
     * @Response(factory="SuccessResponse")
     * @Response(factory="ErrorResponse")
     * @param RoleRequest $request
     * @return mixed
     */
    public function postIndex(RoleRequest $request)
    {
        return DB::try(function () use ($request) {
            $role = new Role(['name' => strtolower(str_replace(' ', '_', $request->name))]);

            if (!$role->save()) {
                return error(trans('role.could_not_save_role'));
            }

            $permissionsNames = [];
            foreach ($request->permissions as $key => $permission) {
                $permissionsNames[] = $request->permissions[$key]['name'];
            }

            $role->givePermissionTo($permissionsNames);

            return success();
        });
    }

    /**
     * Creat new role
     *
     *
     *@Operation()
     *@Response(factory="SuccessResponse")
     *@Response(factory="ErrorResponse")
     * @param RoleRequest $request
     * @return mixed
     */
    public function putIndex(RoleRequest $request)
    {
        return DB::try(function () use ($request) {
            $role       = Role::findOrFail($request->id);
            $role->name = strtolower(str_replace(' ', '_', $request->name));

            $permissionsNames = [];
            foreach ($request->permissions as $key => $permission) {
                $permissionsNames[] = $request->permissions[$key]['name'];
            }

            $role->syncPermissions($permissionsNames);

            if (!$role->save()) {
                return error(trans('role.could_not_update_role'));
            }

            return success();
        });
    }

    /**
     * Remove role
     *
     * @Operation()
     * @Response(factory="SuccessResponse")
     * @Response(factory="ErrorResponse")
     * @param $roleIds
     * @return mixed
     */
    public function deleteIndex($roleIds)
    {
        return DB::try(function () use ($roleIds) {
            $roleIds = explode(',', $roleIds);

            foreach ($roleIds as $roleId) {
                $role = Role::findOrFail($roleId);

                if (!$role->delete()) {
                    return error(trans('role.could_not_delete_role'));
                }
            }

            return success();
        });
    }
}
