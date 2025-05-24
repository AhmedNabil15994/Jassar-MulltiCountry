<?php

namespace Modules\Order\Transformers\WebService;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderLiteResource extends JsonResource
{
    public function toArray($request)
    {
        $allOrderProducts = $this->orderProducts->mergeRecursive($this->orderVariations);
        return [
            'id' => $this->id,
            'order_status' => [
                'flag' => optional($this->orderStatus)->flag,
                'title' => optional($this->orderStatus)->title,
                'color' => optional($this->orderStatus)->color,
                'is_closed' => optional($this->orderStatus)->is_closed ? true : false,
            ],
            'created_at' => date('d-m-Y H:i', strtotime($this->created_at)),
            'products' => count($allOrderProducts),
            'show_route' => route('frontend.orders.invoice',$this->id),
            'address' => (new OrderAddressResource($this->orderAddress))->jsonSerialize(),
        ];
    }
}
