<?php

namespace App\Http\Controllers\API;

use App\Permission;
use App\Http\Controllers\Controller;
use App\Http\Resources\Permission as PermissionResource;
use App\Http\Resources\PermissionCollection;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionAPIController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read_administration_section');
    }

    /**
     * @param mixed $permissionId
     *
     * @return PermissionCollection|ResponseFactory|Response
     */
    public function getIndex(Request $request, $permissionId = null)
    {
        if (empty($permissionId)) {
            return PermissionCollection::make(Permission::select('id', 'name')->tablePaginate($request));
        }

        $permission = Permission::find($permissionId);

        if (empty($permission)) {
            return error('Invalid permission!');
        }

        return success(['permission' => PermissionResource::make($permission)]);
    }
}
