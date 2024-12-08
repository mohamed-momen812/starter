<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class  ProductResource extends JsonResource
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
           'id'          => $this->id,
           'title'       => $this->title,
           'description' => $this->description,
           'price'       => $this->productDetails->price,
           'color'       => $this->productDetails->color,
           'size'        => $this->productDetails->size,
           'user_ID'     => $this->user_id
        ];
    }
}
