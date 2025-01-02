<?php

namespace App\Http\Resources;

use App\Traits\PermissionsTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class  PlanResource extends JsonResource
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
            "id"         => $this->id,
            "name"       => $this->name,
            "price"      => $this->price,
            "duration_days" => $this->duration_days,
            "created_at" => $this->created_at->format('d-m-y'),
            "updated_at" => $this->updated_at->format('d-m-y'),
        ];
    }
}
