<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class  UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id ,
            "first_name" => $this->first_name ,
            "last_name" => $this->last_name ,
            "email" => $this->email ,
            "image" => $this->image ? url($this->image) : null ,
            // "type"  => $this->type,
            // 'role' => RoleResource::collection($this->rolesWithPermissions),
            // "added_permissions"    => $this->permissions->map(function ($permission){
            //     return ['name' => $permission->name];
            // }),
            "created_at" => $this->created_at->format('d-m-y') ,
        ];
    }
}
