<?php

namespace Modules\Catalog\Transformers\Frontend;

use Illuminate\Http\Resources\Json\JsonResource;

class OptionValueResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $option_value = $this->optionValue;

        $data = [
            'id' => $option_value->id,
            'title' => $option_value->title,
            'color' => $option_value->color,
            'status' => optional($this->variant)->status ?? 0,
        ];
        
        return $option_value ? $data : [];
    }
}
