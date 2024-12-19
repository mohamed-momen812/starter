<?php

namespace App\Http\Resources;

use App\Traits\PermissionsTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
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
            'id'            => $this->id,
            'name'          => $this->name,
            'permissions'   => $this->mapPermissions($this->permissions),
        ];
    }


}
