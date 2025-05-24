<?php

namespace Modules\User\Transformers\Frontend;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Area\Entities\Country;
use Modules\Area\Entities\State;

class AddressResource extends JsonResource
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
            'country_id' => optional(optional($this->state)->city)->country_id ?? $this->json_data['country_id'],
            'state_id' => $this->state_id ?? (isset($this->json_data['state_id']) ? $this->json_data['state_id'] : null),
            'country_name' => optional(optional(optional($this->state)->city)->country)->title 
                ?? optional(Country::find($this->json_data['country_id']))->title,
            'state_name' => optional($this->state)->title ?? (isset($this->json_data['state_id']) ? 
                optional(State::find($this->json_data['state_id']))->title : null),
            'phone_code' => $this->phone_code,
            'mobile' => $this->mobile,
            'username' => $this->username,
            'block' => $this->block,
            'street' => $this->street,
            'building' => $this->building,
            'address' => $this->building,
            'default' => $this->is_default ? true : false,
            'delete_loader' => false,
        ];
    }
}
