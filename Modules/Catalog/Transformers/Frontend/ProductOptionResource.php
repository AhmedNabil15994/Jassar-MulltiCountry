<?php

namespace Modules\Catalog\Transformers\Frontend;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductOptionResource extends JsonResource
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
            'option' => (new OptionResource($this->option))->jsonSerialize(),
            'option_values' => OptionValueResource::collection(optional($this->productValues)->unique('option_value_id'))->jsonSerialize(),
        ];
    }
}
