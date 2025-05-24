<?php

namespace Modules\Order\Repositories\FrontEnd;

use Illuminate\Support\Facades\Cache;
use Modules\Order\Traits\OrderCalculationTrait;
use Modules\Catalog\Traits\ShoppingCartTrait;
use Modules\Order\Entities\Order;
use Illuminate\Support\Facades\DB;
use Modules\User\Repositories\FrontEnd\AddressRepository;
use Cart;
use Setting;
use Modules\Order\Entities\OrderStatusesHistory;
use Modules\Variation\Entities\ProductVariant;
use Modules\Order\Entities\PaymentStatus;

class OrderRepository
{
    use OrderCalculationTrait, ShoppingCartTrait;

    protected $order;
    protected $address;
    protected $variantPrd;

    function __construct(Order $order, AddressRepository $address, ProductVariant $variantPrd)
    {
        $this->order = $order;
        $this->address = $address;
        $this->variantPrd = $variantPrd;
    }

    public function getAllByUser($userOrdersIds, $order = 'id', $sort = 'desc')
    {
        $orders = $this->order->with(['orderStatus', 'rate'])->where(function ($q) use ($userOrdersIds) {
            $q->where('user_id', auth()->id());
        })->orderBy($order, $sort)->get();
        return $orders;
    }

    public function getAllGuestOrders($guestOrdersIds, $order = 'id', $sort = 'desc')
    {
        $orders = $this->order->with(['orderStatus', 'rate'])->where(function ($q) use ($guestOrdersIds) {
            $q->whereIn('id', $guestOrdersIds);
            if (auth()->user()) {
                $q->orWhere('user_id', auth()->user()->id);
            }
        })->orderBy($order, $sort)->get();

        return $orders;
    }

    public function findByIdWithGuestId($id)
    {
        $order = $this->order->withDeleted()->find($id);
        return $order;
    }

    public function findById($id)
    {
        $order = $this->order->withDeleted()->find($id);
        return $order;
    }

    public function findByIdWithUserId($id)
    {
        $order = $this->order->withDeleted()->with('rate')->where('user_id', auth()->id())->find($id);
        return $order;
    }

    public function findGuestOrderById($id)
    {
        return $this->order->withDeleted()->with('rate')->find($id);
    }

    public function create($request, $status = false)
    {
        $orderData = $this->calculateTheOrder();

        DB::beginTransaction();

        try {

            $currency_data = [
                'default_currency' => Setting::get('default_currency'),
                'currencies_value' => 1,
                'selected_currency' => CURRENCY ?? Setting::get('default_currency'),
            ];

            $userId = auth()->check() ? auth()->id() : null;
            if ($request['payment'] == 'cash') {
                $orderStatus = 7; // new_order
                $paymentStatus = 4; // cash
            } elseif ($request['payment'] != 'cash' && $orderData['total'] <= 0) {
                $orderStatus = 3; // success
                $paymentStatus = 2; // success
            } else {
                $orderStatus = 1; // pending until payment
                $paymentStatus = 1; // pending
            }

            $orderCreated = $this->order->create([
                'original_subtotal' => $orderData['original_subtotal'],
                'subtotal' => $orderData['subtotal'],
                'off' => $orderData['off'],
                'shipping' => $orderData['shipping'],
                'shipping_details' => $orderData['shipping_details'],
                'total' => $orderData['total'],
                'total_profit' => $orderData['profit'],

                'user_id' => $userId,
                'currency_data' => $currency_data,
                'user_token' => auth()->guest() ? get_cookie_value(config('core.config.constants.CART_KEY')) : null,
                'order_status_id' => $orderStatus,
                'payment_status_id' => $paymentStatus,
                'notes' => $request['notes'] ?? null,
                // 'delivery_time' => $deliveryTime,
            ]);

            $orderCreated->transactions()->create([
                'method' => $request['payment'],
                'result' => ($request['payment'] == 'cache') ? 'CASH' : null,
            ]);

            if (!is_null($orderStatus)) {
                // Add Order Status History
                $orderCreated->orderStatusesHistory()->sync([$orderStatus => ['user_id' => $userId]]);
            }

            $this->createOrderProducts($orderCreated, $orderData);
            $this->createOrderVendors($orderCreated, $orderData['vendors']);
            $this->createOrderCompanies($orderCreated, $request);

            if (!is_null($orderData['coupon'])) {
                $orderCreated->orderCoupons()->create([
                    'coupon_id' => $orderData['coupon']['id'],
                    'code' => $orderData['coupon']['code'],
                    'discount_type' => $orderData['coupon']['type'],
                    'discount_percentage' => $orderData['coupon']['discount_percentage'],
                    'discount_value' => $orderData['coupon']['discount_value'],
                    'products' => $orderData['coupon']['products'],
                ]);
            }

            ############ START To Add Order Address ###################

            ############ START To Add Order Address ###################
            if($request->has('selected_address_id') && $request->selected_address_id){

                // get address by id
                $address = $this->address->findByIdWithoutAuth($request->selected_address_id);
                if ($address)
                    $this->createOrderAddress($orderCreated, $address);
                else
                    return false;
            }else if(session()->has('address_id') && session()->get('address_id')){
                $address = $this->address->findByIdWithoutAuth(session()->get('address_id'));
                if ($address)
                    $this->createOrderAddress($orderCreated, $address);
                else
                    return false;
            }else{

                if ($request->has('json_data') && isset($request->json_data['country_id'])) {

                    $address = $this->createOrderAddress($orderCreated, $request);

                } elseif ($request->address_type == 'known_address') {

                    $address = $this->createUnknownOrderAddress($orderCreated, $request);
                }
            }
            ############ END To Add Order Address ###################

            DB::commit();
            return $orderCreated;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function createOrderProducts($orderCreated, $orderData)
    {
        foreach ($orderData['products'] as $product) {

            if ($product['product_type'] == 'product') {

                $orderProduct = $orderCreated->orderProducts()->create([
                    'product_id' => $product['product_id'],
                    'vendor_id' => $product['vendor_id'],
                    'off' => $product['off'],
                    'qty' => $product['quantity'],
                    'price' => $product['original_price'],
                    'sale_price' => $product['sale_price'],
                    'original_total' => $product['original_total'],
                    'total' => $product['total'],
                    'total_profit' => $product['total_profit'],
                    'notes' => $product['notes'] ?? null,
                    'add_ons_option_ids' => !empty($product['addonsOptions']) && count($product['addonsOptions']) > 0 ? \GuzzleHttp\json_encode($product['addonsOptions']) : null,
                ]);

                $order_product = $orderCreated->orderProducts()->where('product_id',$product['product_id'])->first();
                if (!is_null($order_product->product->qty) && intval($order_product->product->qty) >= intval($product['quantity']))
                    $order_product->product()->decrement('qty', $product['quantity']);

            } else if ($product['product_type'] == 'package'){
                $orderProduct = $orderCreated->orderPackages()->create([
                    'package_id'    => $product['product']['id'],
                    'has_offer'    => $product['product']['off'] ? true : false,
                    'offer_price'    => $product['product']['price'],
                    'total'    => $product['product']['price'],
                    'settings'    => json_encode($product['product']['settings']),
                ]);
                if($orderProduct){
                    $orderProduct->package()->decrement('qty',1);
                    foreach ($orderProduct->package->products as $packageProduct) {
                        if (!is_null($packageProduct->qty) && intval($packageProduct->qty))
                            $packageProduct->decrement('qty',1);
                    }
                }

            }else if ($product['product_type'] == 'offer'){
                $settings = $product['product']['settings'];
                $settings['selectedProducts'] = $product['selectedProducts'];
                $orderProduct = $orderCreated->orderOffers()->create([
                    'offer_id'    => $product['product']['id'],
                    'has_offer'    => $product['off'] ? true : false,
                    'offer_price'    => $product['original_price'],
                    'total'    => $product['original_price'],
                    'settings'    => json_encode($settings),
                    'selected_products' => json_encode($product['selectedProducts']),
                ]);
                if($orderProduct){
                    foreach ($settings['selectedProducts'] as $selectedProduct){
                        $free = $orderProduct->offer->freeProducts()->where('id',$selectedProduct['product_id'])->first();
                        if (!is_null($free->qty) && intval($free->qty))
                            $free->decrement('qty',$selectedProduct['qty']);
                    }

                    foreach ($orderProduct->offer->products as $offerProduct) {
                        if (!is_null($offerProduct->qty) && intval($offerProduct->qty))
                            $offerProduct->decrement('qty',$orderProduct->offer->qty);
                    }
                }
            } else {
                $orderProduct = $orderCreated->orderVariations()->create([
                    'product_variant_id' => $product['product_id'],
                    'vendor_id' => $product['vendor_id'],
                    'off' => $product['off'],
                    'qty' => $product['quantity'],
                    'price' => $product['original_price'],
                    'sale_price' => $product['sale_price'],
                    'original_total' => $product['original_total'],
                    'total' => $product['total'],
                    'total_profit' => $product['total_profit'],
                    'notes' => $product['notes'] ?? null,
                    'add_ons_option_ids' => !empty($product['addonsOptions']) && count($product['addonsOptions']) > 0 ? \GuzzleHttp\json_encode($product['addonsOptions']) : null,
                ]);

                $productVariant = $this->variantPrd->with('productValues')->find($product['product_id']);

                // add product_variant_values to order variations
                if (count($productVariant->productValues) > 0) {
                    foreach ($productVariant->productValues as $k => $value) {
                        $orderProduct->orderVariantValues()->create([
                            'product_variant_value_id' => $value->id,
                        ]);
                    }
                }

                foreach ($orderCreated->orderVariations as $value) {
                    $value->variant()->decrement('qty', $value['qty']);
                }
            }
        }
    }

    public function createOrderVendors($orderCreated, $vendors)
    {
        foreach ($vendors as $k => $vendor) {
            $orderCreated->vendors()->attach($vendor['id'], [
                'total_comission' => $vendor['commission'],
                'total_profit_comission' => $vendor['totalProfitCommission'],
                'original_subtotal' => $vendor['original_subtotal'],
                'subtotal' => $vendor['subtotal'],
                'qty' => $vendor['qty'],
            ]);
        }
    }
    public function createOrderAddress($orderCreated, $address)
    {
        return $orderCreated->orderAddress()->create([
            'username' => $address['username'] ?? optional(auth()->user())->name,
            'email' => $address['email'] ?? optional(auth()->user())->email,
            'phone_code' => $address['phone_code'] ?? optional(auth()->user())->calling_code,
            'mobile' => $address['mobile'] ?? optional(auth()->user())->mobile,
            'address' => $address['address'],
            'block' => $address['block'],
            'street' => $address['street'],
            'building' => $address['building'],
            'avenue' => $address['avenue'] ?? null,
            'floor' => $address['floor'] ?? null,
            'flat' => $address['flat'] ?? null,
            'automated_number' => $address['automated_number'] ?? null,
            'state_id' => $address['address_type'] == 'local' ? $address['state_id'] : null,
            'address_type' => $address['address_type'],
            'json_data' => $address['json_data'],
        ]);
    }

    public function createUnknownOrderAddress($orderCreated, $request)
    {
        return $orderCreated->unknownOrderAddress()->create([
            'receiver_name' => $request->receiver_name,
            'receiver_mobile' => $request->receiver_mobile,
            'state_id' => $request['address_type'] == 'local' ? $request->state_id : null,
            'address_type' => $request['address_type'],
            'json_data' => $request['json_data'],
        ]);
    }

    public function createOrderCompanies($orderCreated, $request)
    {
        if ($this->getDeliveryCompanyFeesCondition() != null)
            $price = $this->getDeliveryCompanyFeesCondition()->getValue();
        else
            $price = 0;

        $data = [
            'company_id' => setting('other.shipping_company') ?? null,
            'delivery' => floatval($price),
        ];

        if (isset($request->shipping_company['day']) && !empty($request->shipping_company['day'])) {
            $dayCode = $request->shipping_company['day'] ?? '';
            $availabilities = [
                'day_code' => $dayCode,
                'day' => getDayByDayCode($dayCode)['day'],
                'full_date' => getDayByDayCode($dayCode)['full_date'],
            ];

            $data['availabilities'] = \GuzzleHttp\json_encode($availabilities);
        }

        if (setting('other.shipping_company')) {
            $orderCreated->companies()->attach(setting('other.shipping_company'), $data);
        }
    }

    /*public function createOrderCompanies($orderCreated, $request)
    {
        foreach ($request->vendor_company as $k => $value) {
            $price = DeliveryCharge::where('state_id', $request->state_id)->where('company_id', $value)->value('delivery');

            $dayCode = $request->vendor_company_day[$k][$value] ?? '';
            $availabilities = [
                'day_code' => $dayCode,
                'day' => getDayByDayCode($dayCode)['day'],
                'full_date' => getDayByDayCode($dayCode)['full_date'],
            ];

            $orderCreated->companies()->attach($value, [
                'vendor_id' => $k,
                'company_id' => $value,
                'availabilities' => \GuzzleHttp\json_encode($availabilities),
                'delivery' => $price,
            ]);
        }
    }*/

    public function getDeliveryCompanyFeesCondition()
    {
        return Cart::getCondition('company_delivery_fees');
    }

    public function updateOrder($request)
    {
        $order = $this->findById($request['OrderID']);
        if (!$order)
            return false;

        if ($request['Result'] != 'CAPTURED' && $order->increment_qty != true) {
            $this->updateQtyOfProduct($order, $request);
        }

        if ($request['Result'] == 'CAPTURED') {
            $newOrderStatus = 7; // new_order
            $newPaymentStatus = optional(PaymentStatus::where('flag', 'success')->first())->id ?? $order->payment_status_id;
            $paymentConfirmedAt = date('Y-m-d H:i:s');
        } else {
            $newOrderStatus = 4; // failed
            $newPaymentStatus = optional(PaymentStatus::where('flag', 'failed')->first())->id ?? $order->payment_status_id;
            $paymentConfirmedAt = null;
        }

        $order->update([
            'order_status_id' => $newOrderStatus,
            'payment_status_id' => $newPaymentStatus,
            'payment_confirmed_at' => $paymentConfirmedAt,
            'increment_qty' => true,
        ]);

        // Add new order history
        $order->orderStatusesHistory()->attach([$newOrderStatus => ['user_id' => $order->user_id ?? null]]);

        $order->transactions()->updateOrCreate(
            [
                'transaction_id' => $request['OrderID']
            ],
            [
                'auth' => $request['Auth'],
                'tran_id' => $request['TranID'],
                'result' => $request['Result'],
                'post_date' => $request['PostDate'],
                'ref' => $request['Ref'],
                'track_id' => $request['TrackID'],
                'payment_id' => $request['PaymentID'],
            ]
        );
        session()->forget('address_id');

        return ($request['Result'] == 'CAPTURED') ? true : false;
    }

    public function updateQtyOfProduct($order, $request)
    {
        foreach ($order->orderProducts as $value) {
            if (!is_null($value->product->qty))
                $value->product()->increment('qty', $value['qty']);

            $variant = $value->orderVariant;
            if (!is_null($variant)) {
                if (!is_null($variant->variant->qty))
                    $variant->variant()->increment('qty', $value['qty']);
            }
        }
    }

    public function updateMyFatoorahOrder($request, $status, $transactionsData, $orderId)
    {
        $order = $this->findById($orderId);
        if (!$order)
            return false;

        if ($status != 'PAID' && $order->increment_qty != true)
            $this->updateQtyOfProduct($order, $request, $status);

        if ($status == 'PAID') {
            $newOrderStatus = 7; // new_order
            $newPaymentStatus = optional(PaymentStatus::where('flag', 'success')->first())->id ?? $order->payment_status_id;
            $paymentConfirmedAt = date('Y-m-d H:i:s');
        } else {
            $newOrderStatus = 4; // failed
            $newPaymentStatus = optional(PaymentStatus::where('flag', 'failed')->first())->id ?? $order->payment_status_id;
            $paymentConfirmedAt = null;
        }

        $order->update([
            'order_status_id' => $newOrderStatus,
            'payment_status_id' => $newPaymentStatus,
            'payment_confirmed_at' => $paymentConfirmedAt,
            'increment_qty' => true,
        ]);

        // Add Order Status History
        OrderStatusesHistory::create([
            'order_id' => $order->id,
            'order_status_id' => $newOrderStatus,
            'user_id' => null,
        ]);

        $transData = !empty($transactionsData) ? [
            'auth' => $transactionsData['AuthorizationId'],
            'tran_id' => $transactionsData['TransactionId'],
            'result' => $status,
            'post_date' => $transactionsData['TransactionDate'],
            'ref' => $transactionsData['ReferenceId'],
            'track_id' => $transactionsData['TrackId'],
            'payment_id' => $transactionsData['PaymentId'],
        ] : [];

        $order->transactions()->updateOrCreate([
            'transaction_id' => $orderId
        ], $transData);
        session()->forget('address_id');
        return $status == 'PAID';
    }
}
