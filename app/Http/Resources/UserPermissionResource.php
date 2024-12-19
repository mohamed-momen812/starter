<?php

namespace App\Http\Resources;

use App\Traits\PermissionsTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class UserPermissionResource extends JsonResource
{
    use PermissionsTrait;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return [
            "role" => RoleResource::collection($this->rolesWithPermissions), // rolesWithPermissions is a custom relation in user model
            'added_permissions' => $this->mapPermissions($this->permissions()->get()),
        ];
    }
}
