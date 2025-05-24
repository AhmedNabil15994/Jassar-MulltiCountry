<?php

namespace Modules\Order\Transformers\WebService;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Catalog\Transformers\WebService\ProductResource;
use Modules\Core\Traits\CoreTrait;

class OrderPackageResource extends JsonResource
{
    use CoreTrait;

    public function toArray($request)
    {
        $currency_data = $this->order->currency_data;
        $result = [
            'selling_price' => priceWithCurrenciesCode($this->total,true,true,$currency_data),
            'qty' => $this->qty,
            'total' => priceWithCurrenciesCode($this->total,true,true,$currency_data),
            'notes' => $this->notes,
        ];

        $result['title'] = $this->package->title;
        $result['image'] = url($this->package->image);

        return $result;
    }
}
