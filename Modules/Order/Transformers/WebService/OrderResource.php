<?php

namespace Modules\Order\Transformers\WebService;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\Transformers\WebService\UserResource;
use Modules\Vendor\Traits\VendorTrait;

class OrderResource extends JsonResource
{
    use VendorTrait;

    public function toArray($request)
    {
        $allOrderProducts = $this->orderProducts->mergeRecursive($this->orderVariations);
        $result = [
            'id' => $this->id,
            'total' => priceWithCurrenciesCode(number_format($this->total, 3),true,true,$this->currency_data),
            'shipping' => priceWithCurrenciesCode(number_format($this->shipping, 3),true,true,$this->currency_data),
            'discount' => priceWithCurrenciesCode(number_format($this->original_subtotal - $this->total, 3),true,true,$this->currency_data),
            'subtotal' => priceWithCurrenciesCode(number_format($this->original_subtotal, 3),true,true,$this->currency_data),
            'transaction' => optional($this->transactions)->method,
            'order_status' => [
                'flag' => optional($this->orderStatus)->flag,
                'title' => optional($this->orderStatus)->title,
                'color' => optional($this->orderStatus)->color,
            ],
            'is_rated' => $this->checkUserRateOrder($this->id),
            'rate' => $this->getOrderRate($this->id),
            'created_at' => date('d-m-Y H:i', strtotime($this->created_at)),
            'notes' => $this->notes,
            'coupon_discount' => $this->getDsicount(),
            'products' => OrderProductResource::collection($allOrderProducts)->jsonSerialize(),
            'offers' => OrderOfferResource::collection($this->orderOffers)->jsonSerialize(),
            'packages' => OrderPackageResource::collection($this->orderPackages)->jsonSerialize(),
            'user' => (new UserResource($this->user))->jsonSerialize(),
        ];

        $result['address'] = (new OrderAddressResource($this->orderAddress))->jsonSerialize();

        /*if (is_null($this->unknownOrderAddress)) {
            $result['address'] = new OrderAddressResource($this->orderAddress);
        } else {
            $result['address'] = new UnknownOrderAddressResource($this->unknownOrderAddress);
        }*/

        if ($this->vendors()->count() > 0) {
            // $request->request->add(['order_id' => $this->id]);
            $result['vendors'] = OrderVendorResource::collection($this->vendors)->jsonSerialize();
        }

        return $result;
    }

    private function getDsicount()
    {
        $coupon = $this->orderCoupons;
        if($coupon){
            return $coupon->discount_type == 'percentage' ? "{$coupon->discount_percentage}%" :
            priceWithCurrenciesCode($coupon->discount_value,true,true,$this->currency_data);
        }
        return null;
    }
}
