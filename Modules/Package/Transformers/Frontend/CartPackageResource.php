<?php

namespace Modules\Package\Transformers\Frontend;

use Illuminate\Http\Resources\Json\JsonResource;

class CartPackageResource extends JsonResource
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
            'id'           => $this->id,
            'title'        => $this->title,
            'category'  => $this->package?->title,
            'image'        => $this->package?->image,
            'slug'         => '',
            'price'        => $this->has_offer_know ? calculateOfferAmountByPercentage($this->price,$this->offer_percentage) : $this->price,
            'sub_title'        => '',
        ];
    }
}
