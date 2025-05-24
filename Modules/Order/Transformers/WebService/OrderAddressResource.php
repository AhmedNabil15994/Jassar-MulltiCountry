<?php

namespace Modules\Order\Transformers\WebService;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderAddressResource extends JsonResource
{
    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'email' => $this->email,
            'phone_code' => $this->phone_code,
            'mobile' => $this->mobile,
            'username' => $this->username,
            'state_id' => $this->state_id,
            'state' => optional($this->state)->title,
            'block' => $this->block,
            'building' => $this->building,
            'street' => $this->street,
            'avenue' => $this->avenue,
            'floor' => $this->floor,
            'flat' => $this->flat,
            'automated_number' => $this->automated_number,
            'additions' => $this->address,
            'city_name' => optional($this->state)->title ?? (isset($this->json_data['city_name']) ? $this->json_data['city_name']:''),
            'json_data' => $this->json_data
        ];

        if (is_null($this->state->city) || is_null($this->state->city->country)) {
            $result['country'] = [
                'id' => null,
                'title' => '',
            ];
        } else {
            $result['country'] = [
                'id' => $this->state->city->country->id,
                'title' => $this->state->city->country->title,
            ];
        }

        return $result;
    }
}
