<?php

namespace Modules\Catalog\Transformers\Frontend;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Package\Transformers\Dashboard\PackagePricesResource;
use Modules\Package\Transformers\Frontend\OfferResource;
use Modules\Package\Transformers\Frontend\PackageResource;

class CartAttributeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $product = $this->product_type != 'variation' ? ($this->product_type == 'product' ? new ProductResource($this->product) : ($this->product_type == 'package' ? new PackageResource($this->product) : new OfferResource($this->product))) : new VariantProductDetailsResource($this->product);
        return [
            'type' => $this->type,
            'sku' => $this->sku,
            'secondary_title' => $this->secondary_title,
            'old_price' => priceWithCurrenciesCode($this->old_price),
            'product_type' => $this->product_type,
            'image' => $product['photo'],
            'product' => $product->jsonSerialize(),
            'notes' => $this->notes,
            'vendor_id' => $this->vendor_id,
            'slug' => $this->slug,
        ];
    }
}
