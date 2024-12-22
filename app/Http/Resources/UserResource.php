<?php

namespace App\Http\Resources;

use App\Traits\PermissionsTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class  UserResource extends JsonResource
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
            "id"         => $this->id,
            "type"       => $this->type,
            "first_name" => $this->first_name,
            "last_name"  => $this->last_name,
            "email"      => $this->email,
            "image"      => $this->images()->first() ? asset('storage/' . $this->images()->first()->path) : null,
            "created_at" => $this->created_at->format('d-m-y'),
            "role" => RoleResource::collection($this->rolesWithPermissions), // rolesWithPermissions is a custom relation in user model
            "added_permissions" => $this->mapPermissions($this->permissions),
        ];
    }
}
