<?php

namespace Modules\Catalog\Http\Controllers\FrontEnd;

use Darryldecode\Cart\CartCondition;
use Modules\Area\Entities\City;
use Modules\Cart\Traits\CartTrait;
use Modules\Catalog\Http\Requests\FrontEnd\AddAddressRequest;
use Modules\Catalog\Http\Requests\FrontEnd\GetDelivryPriceRequest;
use Modules\Coupon\Entities\Coupon;
use Setting;
use Cart;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Area\Entities\State;
use Modules\Catalog\Http\Requests\FrontEnd\CheckoutInformationRequest;
use Modules\Catalog\Traits\ShoppingCartTrait;
use Modules\User\Repositories\FrontEnd\AddressRepository as Address;
use Modules\Catalog\Repositories\FrontEnd\ProductRepository as Product;
use Modules\Company\Entities\DeliveryCharge;
use Modules\Vendor\Repositories\FrontEnd\PaymentRepository as PaymentMethods;
use Modules\Company\Repositories\FrontEnd\CompanyRepository as Company;
use Modules\Core\Traits\CoreTrait;
use Modules\Vendor\Repositories\FrontEnd\VendorRepository as VendorRepo;
use Modules\Area\Repositories\FrontEnd\CountryRepository;
use Modules\Vendor\Entities\Vendor;
use Modules\Vendor\Entities\VendorDeliveryCharge;
use Modules\User\Transformers\Frontend\AddressResource;
use Modules\Shipping\Traits\ShippingTrait;

class CheckoutController extends Controller
{
    use ShoppingCartTrait, CoreTrait,ShippingTrait;

    protected $product;
    protected $payment;
    protected $company;
    protected $vendor;
    protected $address;
    protected $country;

    function __construct(Product $product, PaymentMethods $payment, Company $company, VendorRepo $vendor,Address $address, CountryRepository $country)
    {
        $this->product = $product;
        $this->payment = $payment;
        $this->company = $company;
        $this->vendor = $vendor;
        $this->address = $address;
        $this->country = $country;
    }

    public function index(Request $request)
    {
        if (setting('other.select_shipping_provider') == 'shipping_company') {
            $companyId = setting('other.shipping_company') ?? 0;
            $deliveryProvider = $this->company->findById($companyId, ['deliveryTimes']);
        } else {
            $vendorId = getCartContent()->first()->attributes['vendor_id'] ?? null;
            $deliveryProvider = $this->vendor->findById($vendorId, ['deliveryTimes']);
        }

        $deliveryTimes = [];
        $deliveryProviderId = null;
        if ($deliveryProvider && !empty($deliveryProvider->delivery_time_types)) {
            $deliveryTimes = $this->buildDeliveryTimes($deliveryProvider);
            $deliveryProviderId = $deliveryProvider->id;
        }

        if(auth()->check())
            $addresses = AddressResource::collection($this->address->getAllByUsrId())->jsonSerialize();
        else
            $addresses = [];

        $countries = $this->country->getAllSuported();
        $cities = $this->country->getCitiesWithStatesByCountryId(currentCountry());
        $paymentMethods = $this->getSuportedPaymentMethods();
        return view('catalog::dukaan.checkout.index', compact('paymentMethods', 'deliveryTimes', 'deliveryProviderId','addresses','countries','cities'));
    }

    public function getSuportedPaymentMethods()
    {
        $paymentMethod = array_filter(Setting::get('payment_gateway') ?? [],function($getway){

            return $getway['status'] == 'on';
        });

        $paymentMethod = array_map(function($key,$getway){

            return [
                'key' => $key,
                'title' => $getway['title_'.locale()],
                'logo' => "/dukaan/images/".Setting::get("{$key}_logo"),
            ];
        },array_keys($paymentMethod),$paymentMethod);
        return $paymentMethod;
    }

    public function saveCheckoutInformation(CheckoutInformationRequest $request)
    {
        abort(404);
    }

    public function getContactInfo(Request $request)
    {
        $savedContactInfo = !empty(get_cookie_value(config('core.config.constants.CONTACT_INFO'))) ? (array)\GuzzleHttp\json_decode(get_cookie_value(config('core.config.constants.CONTACT_INFO'))) : [];
        return view('catalog::frontend.checkout.index', compact('savedContactInfo'));
    }

    public function getPaymentMethods(Request $request)
    {
        $cartAttributes = isset(Cart::getConditions()['delivery_fees']) && !empty(Cart::getConditions()['delivery_fees']) ? Cart::getConditions()['delivery_fees']->getAttributes() : null;

        if ($cartAttributes && $cartAttributes['address'] != null) {

            $address = Cart::getCondition('delivery_fees')->getAttributes()['address'];
            $vendor = Vendor::find(Cart::getCondition('vendor')->getType());

            return view('catalog::frontend.checkout.index', compact('address', 'vendor'));
        } else {
            return redirect()->back();
        }
    }

    public function getStateDeliveryPrice(GetDelivryPriceRequest $request)
    {
        if (auth()->check())
            $userToken = auth()->user()->id ?? null;
        else
            $userToken = get_cookie_value(config('core.config.constants.CART_KEY')) ?? null;

        if (is_null($userToken))
            return response()->json(["errors" => __('apps::frontend.general.user_token_not_found')], 422);

        if (isset($request->address_type) && in_array($request->address_type,['selected_address','known_address'])) {

            $request->company_id = setting('other.shipping_company') ?? 0;
            $address = null;

            if ($request->address_type == 'known_address') {

                $this->setShippingTypeByRequest($request);

            }elseif($request->address_type == 'selected_address'){

                $address = $request->selected_address_id ? \Modules\User\Entities\Address::find($request->selected_address_id): null;

                if ($address)
                    $this->setShippingTypeByAddress($address);
                else
                    $this->setShippingTypeByRequest($request);

            } else {
                return response()->json(['success' => false, 'errors' => __('catalog::frontend.checkout.validation.please_choose_state')], 422);
            }

            return $this->shipping->getDeliveryPrice($request,$address,$userToken);

        } else {
            $data = [
                'original_price' => 0,
                'price' => null,
                'totalDeliveryPrice' => 0,
                'total' => priceWithCurrenciesCode(number_format(getCartTotal(), 3)),
            ];
            return response()->json(['success' => true, 'data' => $data]);
        }
    }

    public function getCities(Request $request,$countryPrefix,$countryId){
        return response()->json([
            'success' => true,
            'data' => City::with('states')->where('country_id',$countryId)->get()
        ]);
    }

    public function createAddress(AddAddressRequest $request){
        if(!auth()->check()){
            $request->request->add(['automated_number'=>get_cookie_value(config('core.config.constants.CART_KEY'))]);
        }

        $address = $this->address->create($request);
        if($address){
            session()->put('address_id',$address->id);

            if (auth()->check())
                $userToken = auth()->user()->id ?? null;
            else
                $userToken = get_cookie_value(config('core.config.constants.CART_KEY')) ?? null;

            if (is_null($userToken))
                return redirect()->back()->withErrors(__('apps::frontend.general.user_token_not_found'));

            if ((isset($request->address_type) && in_array($request->address_type,['selected_address','known_address','local'])) || $request->country_id) {

                $request->company_id = config('setting.other.shipping_company') ?? 0;
                if ($request->address_type == 'known_address' || $request->country_id) {
                    $this->setShippingTypeByRequest($request);
                }elseif($request->address_type == 'selected_address'){
                    if ($address)
                        $this->setShippingTypeByAddress($address);
                    else
                        $this->setShippingTypeByRequest($request);
                } else {
                    return redirect()->back()->withErrors( __('catalog::frontend.checkout.validation.please_choose_state'));
                }
                $this->shipping->getDeliveryPrice($request,$address,$userToken);
            }

            return redirect()->route('frontend.checkout.complete');
        }
    }

    public function complete(){
        if (setting('other.select_shipping_provider') == 'shipping_company') {
            $companyId = setting('other.shipping_company') ?? 0;
            $deliveryProvider = $this->company->findById($companyId, ['deliveryTimes']);
        } else {
            $vendorId = getCartContent()->first()->attributes['vendor_id'] ?? null;
            $deliveryProvider = $this->vendor->findById($vendorId, ['deliveryTimes']);
        }


        $deliveryTimes = [];
        $deliveryProviderId = null;
        $mainAddress = null;
        $deliveryCharge = null;
        if ($deliveryProvider && !empty($deliveryProvider->delivery_time_types)) {
            $deliveryTimes = $this->buildDeliveryTimes($deliveryProvider);
            $deliveryProviderId = $deliveryProvider->id;
        }

        if(auth()->check()){
            $addresses = AddressResource::collection($this->address->getAllByUsrId())->jsonSerialize();
            $mainAddress = $this->address->getAllByUsrId()->first();
        }else{
            $addresses = [];
            if(session()->has('address_id')){
                $mainAddress = $this->address->findByIdWithoutAuth(session()->get('address_id'));
            }
        }
        if($mainAddress){
            $deliveryCharge = $deliveryProvider->deliveryCharge()->active()->where('state_id',$mainAddress->state_id)->first();
        }
        $this->addCompanyDeliveryFeesCondition($deliveryCharge,$mainAddress);
        $paymentMethods = $this->getSuportedPaymentMethods();
        $countries = $this->country->getAllSuported();
        $cities = $this->country->getCitiesWithStatesByCountryId(currentCountry());
        return view('catalog::dukaan.checkout.checkout', compact('paymentMethods','addresses', 'deliveryTimes', 'deliveryProviderId','countries','cities','deliveryCharge'));
    }

    public function addCompanyDeliveryFeesCondition($deliveryFeesObject,$mainAddress)
    {
        if (auth()->check())
            $userToken = auth()->user()->id ?? null;
        else
            $userToken = get_cookie_value(config('core.config.constants.CART_KEY')) ?? null;

        if ($deliveryFeesObject) {
            $translatedDeliveryTimeNotes = $deliveryFeesObject->delivery_time;
            Cart::session($userToken)->removeCartCondition('company_delivery_fees');

            if(Cart::session($userToken)->getTotal() > $deliveryFeesObject->min_order_for_free_delivery){
                return $this->companyDeliveryChargeCondition($userToken,$mainAddress,$deliveryFeesObject->id, 0, $translatedDeliveryTimeNotes, floatval($deliveryFeesObject->delivery));
            }

            $couponCondition = Cart::session($userToken)->getCondition( 'coupon_discount');
            if (!is_null($couponCondition)) {
                $currentCouponModel = $this->getCurrentCoupon($couponCondition->getAttributes()['coupon']->id);
                if ($currentCouponModel) {
                    if ($currentCouponModel->free_delivery == 1) {
                        $this->companyDeliveryChargeCondition($userToken,$mainAddress,$deliveryFeesObject->id, 0, $translatedDeliveryTimeNotes, floatval($deliveryFeesObject->delivery));
                    } else {
                        $this->companyDeliveryChargeCondition($userToken,$mainAddress,$deliveryFeesObject->id, floatval($deliveryFeesObject->delivery), $translatedDeliveryTimeNotes);
                    }
                } else {
                    // delete existing coupon condition
                    Cart::session($userToken)->removeCartCondition('coupon_discount');
                    $this->companyDeliveryChargeCondition($userToken,$mainAddress,$deliveryFeesObject->id, floatval($deliveryFeesObject->delivery), $translatedDeliveryTimeNotes);
                }
            } else {
                $this->companyDeliveryChargeCondition($userToken,$mainAddress,$deliveryFeesObject->id, floatval($deliveryFeesObject->delivery), $translatedDeliveryTimeNotes);
            }

        } else {
            Cart::session($userToken)->removeCartCondition('company_delivery_fees');
            return response()->json(["errors" =>__('catalog::frontend.checkout.validation.state_not_supported_by_company')], 422);
        }
    }

    public function getCurrentCoupon($id)
    {
        return Coupon::where('start_at', '<=', date('Y-m-d'))
            ->where('expired_at', '>', date('Y-m-d'))
            ->active()
            ->find($id);
    }

    public function companyDeliveryChargeCondition($userToken,$address,$charge_id, $price, $delivery_time = null, $oldValue = null)
    {
        $cart = Cart::session($userToken);

        $deliveryFees = new CartCondition([
            'name' => 'company_delivery_fees',
            'type' => 'company_delivery_fees',
            'target' => 'total',
            'value' => (string) $price,
            'attributes' => [
                'city_id' => $address->state->city->id,
                'country_id' => $address->state->country_id,
                'state_id' => $address->state_id,
                'address_id' => $address->id ?? null,
                'vendor_id' => null,
                'delivery_charge_id' => $charge_id,
                'delivery_time_note' => $delivery_time,
                'old_value' => $oldValue,
            ],
        ]);

        $cart->condition([$deliveryFees]);
        return true;
    }

    protected function returnCustomResponse($request)
    {
        return [
            'conditions' => $this->getCartConditions($request),
            'subTotal' => number_format($this->cartSubTotal($request), 3),
            'total' => number_format($this->cartTotal($request), 3),
            'count' => $this->cartCount($request),
        ];
    }
}
