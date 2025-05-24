<?php

namespace Modules\Catalog\Transformers\Frontend;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailsResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'sku' => $this->sku,
            'slug' => $this->slug,
            'qty' => $this->qty,
            'variants_count' => $this->variants_count,
            'category' => implode(', ',$this->categories->pluck('title')->toArray()),
            'photo' => $this->image_path,
            'gallary' => PhotoResource::collection($this->getMedia('gallery')),
            'tags' => $this->tags,
            'price' => $this->price,
            'product_flag' => $this->product_flag,
            'options' => ProductOptionResource::collection($this->options)->jsonSerialize(),
            // 'addons' => ProductAddonResource::collection($this->addons)->jsonSerialize(),
            'cart_loader' => false,
        ];

        return getDukaanProductPrice($this,$data);
    }
}
