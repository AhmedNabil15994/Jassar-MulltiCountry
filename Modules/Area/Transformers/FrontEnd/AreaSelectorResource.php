<?php

namespace Modules\Area\Transformers\FrontEnd;

use Illuminate\Http\Resources\Json\JsonResource;

class AreaSelectorResource extends JsonResource
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
            'id' => $this->id,
            'title' => $this->title,
            'states' => StateResource::collection($this->states()->has('activeDeliveryCharge')->get())->jsonSerialize(),
        ];
    }
}
