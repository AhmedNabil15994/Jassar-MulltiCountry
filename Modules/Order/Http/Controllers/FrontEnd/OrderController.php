<?php

namespace Modules\Order\Http\Controllers\FrontEnd;

use Cart;
use Modules\Company\Entities\DeliveryCharge;
use Modules\Order\Transformers\WebService\OrderLiteResource;
use Modules\Order\Transformers\WebService\OrderResource;
use Setting;
use Notification;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\MessageBag;
use Modules\Order\Events\ActivityLog;
use Modules\Order\Events\VendorOrder;
use Modules\Catalog\Traits\ShoppingCartTrait;

use Modules\Transaction\Services\TapPaymentService;
use Modules\Transaction\Services\MyFatoorahPaymentService;

use Modules\Order\Http\Requests\FrontEnd\CreateOrderRequest;
use Modules\Order\Repositories\FrontEnd\OrderRepository as Order;
use Modules\Catalog\Repositories\FrontEnd\ProductRepository as Product;
use Modules\Package\Repositories\Frontend\OfferRepository as Offer;
use Modules\Vendor\Repositories\FrontEnd\VendorRepository as Vendor;
use Modules\Package\Repositories\Frontend\PackageRepository as Package;
use Modules\Vendor\Traits\VendorTrait;
use Modules\Shipping\Traits\ShippingTrait;
use Modules\User\Entities\Address;
use Modules\Transaction\Traits\PaymentTrait;

class OrderController extends Controller
{
    use ShoppingCartTrait, ShippingTrait;

    protected $payment;
    protected $myFatoorahPayment;
    protected $order;
    protected $product;
    protected $vendor;
    protected $package;
    protected $offer;

    function __construct(
        Order $order,
        Product $product,
        Vendor $vendor,
        Package $package,
        Offer $offer
    ) {
        $this->order = $order;
        $this->product = $product;
        $this->vendor = $vendor;
        $this->package = $package;
        $this->offer = $offer;
    }

    public function index()
    {
        $ordersIDs = isset($_COOKIE[config('core.config.constants.ORDERS_IDS')]) && !empty($_COOKIE[config('core.config.constants.ORDERS_IDS')]) ? (array)\GuzzleHttp\json_decode($_COOKIE[config('core.config.constants.ORDERS_IDS')]) : [];

        if (auth()->user()) {
            $orders = OrderLiteResource::collection($this->order->getAllByUser($ordersIDs));

            return view('order::dukaan.orders.index', compact('orders'));
        } else {
            $orders = count($ordersIDs) > 0 ? OrderLiteResource::collection($this->order->getAllGuestOrders($ordersIDs)): [];
            return view('order::dukaan.orders.index', compact('orders'));
        }
    }

    public function invoice($countryPrefix,$id)
    {
        if (auth()->user())
            $order = $this->order->findByIdWithUserId($id);
        else
            $order = $this->order->findGuestOrderById($id);

        if (!$order)
            return abort(404);

        $order = (new OrderResource($order))->jsonSerialize();
        return view('order::dukaan.orders.invoice', compact('order'));
    }

    public function reOrder($countryPrefix,$id)
    {
        $order = $this->order->findByIdWithUserId($id);
        $order->orderProducts = $order->orderProducts->mergeRecursive($order->orderVariations);
        return view('order::frontend.orders.re-order', compact('order'));
    }

    public function guestInvoice()
    {
        $savedID = [];
        if (isset($_COOKIE[config('core.config.constants.ORDERS_IDS')]) && !empty($_COOKIE[config('core.config.constants.ORDERS_IDS')])) {
            $savedID = (array)\GuzzleHttp\json_decode($_COOKIE[config('core.config.constants.ORDERS_IDS')]);
        }
        $id = count($savedID) > 0 ? $savedID[count($savedID) - 1] : 0;
        $order = $this->order->findByIdWithGuestId($id);
        if (!$order)
            abort(404);

        $order->orderProducts = $order->orderProducts->mergeRecursive($order->orderVariations);
        return view('order::dukaan.orders.guest-order', compact('order'));
    }

    public function createOrder(CreateOrderRequest $request)
    {

        $address = $request->address_type == 'selected_address' ? Address::find($request->selected_address_id): null;

        if($address){

            $this->setShippingTypeByAddress($address);
            $shippingValidateAddress = $this->shipping->validateAddress($request,$address);
        }else{

            $this->setShippingTypeByRequest($request);
            $shippingValidateAddress = $this->shipping->validateAddress($request);

        }

        if($shippingValidateAddress[0]){

            $errors = new MessageBag([
                'productCart' => [$shippingValidateAddress],
            ]);
            return response()->json(['message' => $errors->first(), 'errors' => $errors], 422);

        }else{

            $request->merge(['address_type' => $shippingValidateAddress['addressType'], 'json_data' => $shippingValidateAddress['jsonData']]);
        }

        $payment = $request['payment'] != 'cache' ? PaymentTrait::getPaymentGateway($request['payment']) : 'cache';

        if ($payment != 'cache' && !$payment) {

            return response()->json(['message' => __('order::frontend.orders.index.alerts.payment_not_supported_now')], 422);

        }elseif($payment == 'cache' && $request->has('json_data') && isset($request->json_data['country_id'])
            && count((array)Setting::get("payment_gateway.cache.supported_countries"))
            && !in_array($request->json_data['country_id'], (array)Setting::get("payment_gateway.cache.supported_countries"))){

                return response()->json(['message' => __('order::frontend.orders.index.alerts.country_not_support_cache_payment')], 422);
        }

        $errors1 = [];
        $errors2 = [];
        $errors3 = [];
        $errors4 = [];
        $errors5 = [];

        $total = 0;
        foreach (getCartContent() as $key => $item) {

            if ($item->attributes->product->product_type == 'product') {
                $cartProduct = $item->attributes->product;
                $product = $this->product->findOneProduct($cartProduct->id);
                if (!$product)
                    return response()->json(['message' => __('cart::api.cart.product.not_found') . $cartProduct->id,'errors' => []],422);

                $product->product_type = 'product';
            } else if ($item->attributes->product->product_type == 'package') {
                $cartProduct = $item->attributes->product;
                $product = $this->package->findPackageById($cartProduct->id);
                if (!$product)
                    return response()->json(['message' => __('cart::api.cart.package.not_found') . $cartProduct->id,'errors' => []],422);

                $product->product_type = 'package';
            }else if ($item->attributes->product->product_type == 'offer') {
                $cartProduct = $item->attributes->product;
                $product = $this->offer->findOfferById($cartProduct->id);
                if (!$product)
                    return response()->json(['message' => __('cart::api.cart.offer.not_found') . $cartProduct->id,'errors' => []],422);

                $product->product_type = 'offer';
                $product->price = $item->price;
                $product->selectedProducts = $item->attributes->product->selectedProducts;
            }

            $total+= $item->price * $item->quantity;

            $productFound = $this->productFound($product, $item);
            if ($productFound) {
                $errors1[] = $productFound;
            }

            // $activeStatus = $this->checkActiveStatus($product, $request);
            $activeStatus = $this->checkProductStatus($product);
            if ($activeStatus) {
                $errors2[] = $activeStatus;
            }

            if (!is_null($product->qty)) {
                if($item->attributes->product->product_type == 'product'){
                    $maxQtyInCheckout = $this->checkMaxQtyInCheckout($product, $item->quantity, $product->qty);
                    if ($maxQtyInCheckout) {
                        $errors3[] = $maxQtyInCheckout;
                    }
                }else if($item->attributes->product->product_type == 'package'){
                    $maxQtyInCheckout = $this->checkMaxQtyInCheckout($product, $item->quantity, $cartProduct->qty);
                    if ($maxQtyInCheckout) {
                        $errors3[] = $maxQtyInCheckout;
                    }
                    foreach ($item->attributes->product->products as $firstProd){
                        $maxQtyInCheckout = $this->checkMaxQtyInCheckout($firstProd, 1, 1);
                        if ($maxQtyInCheckout) {
                            $errors3[] = $maxQtyInCheckout;
                        }
                    }

                }else if($item->attributes->product->product_type == 'offer'){
                    $maxQtyInCheckout = $this->checkMaxQtyInCheckout($product, $item->quantity, $cartProduct->qty);
                    if ($maxQtyInCheckout) {
                        $errors3[] = $maxQtyInCheckout;
                    }

                    foreach ($item->attributes->product->products as $firstProd){
                        $maxQtyInCheckout = $this->checkMaxQtyInCheckout($firstProd, $cartProduct->qty, $cartProduct->qty);
                        if ($maxQtyInCheckout) {
                            $errors3[] = $maxQtyInCheckout;
                        }
                    }

                    foreach ($item->attributes->product->freeProducts as $freeProd){
                        $qty = 1;
                        foreach($item->attributes->product->selectedProducts as $freeItem){
                            if($freeItem['product_id'] == $freeProd->id){
                                $qty = $freeItem['qty'];
                            }
                        }

                        $maxQtyInCheckout = $this->checkMaxQtyInCheckout($freeProd, $qty, $qty);
                        if ($maxQtyInCheckout) {
                            $errors3[] = $maxQtyInCheckout;
                        }
                    }
                }

            }

            $vendorStatusError = $this->vendorStatus($product);
            if ($vendorStatusError) {
                $errors4[] = $vendorStatusError;
            }

            if (!is_null($product->qty)) {
                $checkPrdQty = $this->checkQty($product);
                if ($checkPrdQty) {
                    $errors5[] = $checkPrdQty;
                }
            }
        }

        $fees = getCartConditionByName(null,'company_delivery_fees');
        if(!is_null($fees)){
            $charge = DeliveryCharge::find($fees->getAttributes()['delivery_charge_id']);
            $min_order_amount = $charge->min_order_amount;
            if(floatval($total) < floatval($min_order_amount)){
                return response()->json(['message' => __('order::frontend.orders.index.alerts.minimum_order_value_must_be_greater_than') . ' '.$min_order_amount.' '.CURRENCY], 422);
            }
        }

        if ($errors1 || $errors2 || $errors3 || $errors4 || $errors5) {
            $errors = new MessageBag([
                'productCart' => $errors1,
                'productCart2' => $errors2,
                'productCart3' => $errors3,
                'productCart4' => $errors4,
                'productCart5' => $errors5,
            ]);
            return response()->json(['message' => $errors->first(),'errors' => $errors],422);
        }


        $order = $this->order->create($request);
        if (!$order) {

            return response()->json(['message' => __('order::frontend.orders.index.alerts.order_failed')], 422);
        }

        if ($payment != 'cache') {
            $redirect = $payment->send($order, 'online', auth()->check() ? auth()->id() : null);

            if (isset($redirect['status'])) {

                if ($redirect['status'] == true) {
                    return response()->json(['url' => $redirect['url']]);
                } else {
                    return response()->json(['message' => 'Online Payment not valid now'], 422);
                }
            }

            return 'field';
        }

        $this->shipping->createShipment($request,$order);
        session()->forget('address_id');

        return $this->redirectToPaymentOrOrderPage($request, $order,true);
    }
    public function webhooks(Request $request)
    {
        $this->order->updateOrder($request);
    }

    public function success(Request $request)
    {
        $order = $this->order->updateOrder($request);
        return $order ? $this->redirectToPaymentOrOrderPage($request) : $this->redirectToFailedPayment();
    }

    public function successTap(Request $request)
    {
        $data = (new TapPaymentService())->getTransactionDetails($request);

        $request = PaymentTrait::buildTapRequestData($data, $request);

        if ($request->Result == 'CAPTURED') {
            return $this->success($request);
        }
        return $this->failed($request);

    }

    public function myFatoorahCallBack(Request $request)
    {
        $data = (new MyFatoorahPaymentService())->GetPaymentStatus($request->paymentId , 'paymentId');

        $request = PaymentTrait::buildMyFatoorahRequestData($data, $request);

        if ($request->Result == 'CAPTURED') {
            return $this->success($request);
        }
        return $this->failed($request);

    }

    public function failed(Request $request)
    {
        $this->order->updateOrder($request);
        return $this->redirectToFailedPayment();
    }

    public function redirectToPaymentOrOrderPage($data, $order = null,$json = false)
    {
        $order = ($order == null) ? $this->order->findById($data['OrderID']) : $this->order->findById($order->id);
        try {

            if ($this->sendNotifications($order)) {

            }
        } catch (\Exception $e) {
            info($e);
        }
        $this->clearCart();
        return $this->redirectToInvoiceOrder($order,$json);
    }

    public function redirectToInvoiceOrder($order,$json = false)
    {
        ################# Start Store Guest Orders In Browser Cookie ######################
        if (isset($_COOKIE[config('core.config.constants.ORDERS_IDS')]) && !empty($_COOKIE[config('core.config.constants.ORDERS_IDS')])) {
            $cookieArray = (array) \GuzzleHttp\json_decode($_COOKIE[config('core.config.constants.ORDERS_IDS')]);
        }
        $cookieArray[] = $order['id'];
        setcookie(config('core.config.constants.ORDERS_IDS'), \GuzzleHttp\json_encode($cookieArray),
            time() + (5 * 365 * 24 * 60 * 60), '/'); // expires at 5 year
        ################# End Store Guest Orders In Browser Cookie ######################

        if (auth()->user()) {

            if($json)
                return response()->json(['success' => true, 'url' => route('frontend.orders.invoice', $order->id)]);
            else
                return redirect()->route('frontend.orders.invoice', $order->id)->with([
                    'alert' => 'success', 'status' => __('order::frontend.orders.index.alerts.order_success')
                ]);
        }
        if($json)
            return response()->json(['success' => true, 'url' => route('frontend.orders.guest.invoice')]);
        else
            return redirect()->route('frontend.orders.guest.invoice');
    }

    public function redirectToFailedPayment()
    {
        return redirect()->route('frontend.checkout.index')->with([
            'alert' => 'danger', 'status' => __('order::frontend.orders.index.alerts.order_failed')
        ]);
    }

    public function sendNotifications($order)
    {
        $this->fireLog($order);

        if ($order->orderAddress) {
//            Notification::route('mail', $order->orderAddress->email)->notify(
//                (new UserNewOrderNotification($order))->locale(locale())
//            );
        }

//        Notification::route('mail', setting('contact_us.email'))->notify(
//            (new AdminNewOrderNotification($order))->locale(locale())
//        );
    }

    public function fireLog($order)
    {
        try {

            $data = [
                'id' => $order->id,
                'type' => 'orders',
                'url' => url(route('dashboard.orders.show', $order->id)),
                'description_en' => 'New Order',
                'description_ar' => 'طلب جديد ',
            ];

            event(new ActivityLog($data));

        } catch (\Exception $e) {
            info($e);
        }
    }
}
