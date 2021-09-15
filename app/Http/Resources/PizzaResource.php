<?php

namespace App\Http\Resources;

use App\Models\Review;
use Illuminate\Http\Resources\Json\JsonResource;

class PizzaResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'variant_id' => $this->variant_id,
            'description' => $this->description,
            'price' => $this->price,
            'images' => $this->images,
            'reveiws' => ReveiwResource::collection($this->reviews),
            'variant' => new VariantResource($this->variant)
        ];
    }
}
