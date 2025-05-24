<?php

namespace Modules\Package\Transformers\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class PackageCardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id"           => $this->id,
            "title"        => $this->title,
            'has_offer_know'        => $this->has_offer_know,
            'price'        => number_format($this->price,3),
            'offer_price'        => number_format(calculateOfferAmountByPercentage($this->price,$this->offer_percentage),3),
            'description'        => explode(',',$this->subscribe_duration_desc),
        ];
    }
}
