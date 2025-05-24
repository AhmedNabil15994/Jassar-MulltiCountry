<?php

namespace Modules\Vendor\Transformers\WebService;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Catalog\Transformers\WebService\PaginatedResource;
use Modules\Catalog\Transformers\WebService\ProductResource;
use Modules\Vendor\Traits\VendorTrait;

class VendorResource extends JsonResource
{
    use VendorTrait;

    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'image' => $this->image ? url($this->image) : null,
            'title' => $this->title,
            'description' => $this->description,
            'rate' => $this->getVendorRate($this->id),
            'address' => $this->address ?? null,
            'mobile' => !is_null($this->mobile) ? /*$this->calling_code .*/ $this->mobile : null,

            /*'payments' => PaymenteResource::collection($this->payments),
            'fixed_delivery' => $this->fixed_delivery,
            'order_limit' => $this->order_limit,
            'rate' => $this->getVendorTotalRate($this->rates),*/
        ];

        $result['opening_status'] = $this->checkVendorBusyStatus($this->id);
        if ($request->with_vendor_categories == 'yes') {
            $result['vendor_categories'] =  CategoryResource::collection($this->categories);
        }
        return $result;
    }
}
