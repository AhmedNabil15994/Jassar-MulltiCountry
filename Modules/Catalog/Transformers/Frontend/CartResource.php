<?php

namespace Modules\Catalog\Transformers\Frontend;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
       return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => priceWithCurrenciesCode($this->price),
            'qty' => $this->quantity,
            'attributes' => (new CartAttributeResource($this->attributes))->jsonSerialize(),
            'conditions' => $this->conditions,
        ];
    }
}
