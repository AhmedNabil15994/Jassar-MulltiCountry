<?php

namespace Modules\Package\Transformers\Frontend;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Catalog\Transformers\Frontend\ProductResource;

class OfferResource extends JsonResource
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
            "id"           => $this->id,
            "title"        => $this->title,
            "description"  => $this->description,
            "status"     => $this->status,
            'photo'      => $this->photo,
            "qty"    => $this->qty,
            "free_qty"    => $this->free_qty,
            "price"    => $this->price,
            "selectedProducts"    => $this->selectedProducts,
            'total_qty'  => $this->qty + $this->free_qty,
            'products'  => ProductResource::collection($this->products)->jsonSerialize(),
            "created_at" => $this->created_at->format("d-m-Y H:i a"),
            "deleted_at" => $this->deleted_at

        ];
    }
}
