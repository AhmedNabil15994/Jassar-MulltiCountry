<?php

namespace Modules\Catalog\Transformers\Frontend;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductAddonResource extends JsonResource
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
            'type' => $this->type,
            'addon_category' => $this->addonCategory->title,
        ];
    }
}
