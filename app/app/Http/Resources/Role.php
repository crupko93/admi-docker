<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Role extends JsonResource
{
    public static $wrap = 'role';

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'label'       => $this->label,
            'permissions' => PermissionCollection::make($this->whenLoaded('permissions')),
        ];
    }
}
