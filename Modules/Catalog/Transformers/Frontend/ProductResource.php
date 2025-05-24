<?php

namespace Modules\Catalog\Transformers\Frontend;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'slug' => $this->slug,
            'photo' => $this->image_path,
            'show_route' => route('frontend.products.index', $this->slug),
            'tags' => $this->tags,
            'variants_count' => $this->variants_count,
            'product_flag' => $this->product_flag,
            'price' => $this->price,
            'base_price' => $this->price,
            'cart_loader' => false,
        ];

        return getDukaanProductPrice($this,$data);
    }
}
