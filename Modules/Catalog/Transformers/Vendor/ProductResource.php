<?php

namespace Modules\Catalog\Transformers\Vendor;

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
        return [
            'id' => $this->id,
            'title' => $this->title,
            'image' => $this->image ? url($this->image) : null,
            'status' => ajaxSwitch($this, url(route('vendor.products.switch', [$this->id, 'status']), 'vendor_dashboard')),
            'print_status' => $this->status ? __('apps::dashboard.datatable.active') :  __('apps::dashboard.datatable.unactive'),
            'price' => $this->price,
            'vendor' => optional($this->vendor)->title,
            'deleted_at' => $this->deleted_at,
            'created_at' => date('d-m-Y', strtotime($this->created_at)),
        ];
    }
}
