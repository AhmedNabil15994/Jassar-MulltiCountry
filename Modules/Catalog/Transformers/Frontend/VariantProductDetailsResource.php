<?php

namespace Modules\Catalog\Transformers\Frontend;

use Illuminate\Http\Resources\Json\JsonResource;

class VariantProductDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'sku' => $this->sku,
            'qty' => $this->qty,
            'photo' => $this->image_url,
            'price' => $this->price,
            'parent_slug' => $this->product->slug,
            'cart_loader' => false,
        ];

        return getDukaanProductPrice($this,$data);
    }
}
