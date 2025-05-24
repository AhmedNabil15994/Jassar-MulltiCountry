<?php

namespace Modules\User\Http\Controllers\FrontEnd;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Authentication\Traits\AuthenticationTrait;
use Modules\User\Http\Requests\FrontEnd\UpdateProfileRequest;
use Modules\User\Http\Requests\FrontEnd\UpdatePasswordRequest;
use Modules\User\Http\Requests\FrontEnd\UpdateAddressRequest;
use Modules\User\Repositories\FrontEnd\UserRepository as User;
use Modules\User\Repositories\FrontEnd\AddressRepository as Address;
use Modules\Area\Repositories\FrontEnd\CountryRepository;
use Modules\Shipping\Traits\ShippingTrait;
use Modules\User\Transformers\Frontend\AddressResource;

class UserController extends Controller
{
    use AuthenticationTrait,ShippingTrait;

    protected $user;
    protected $address;
    protected $country;

    function __construct(User $user, Address $address, CountryRepository $country)
    {
        $this->user = $user;
        $this->address = $address;
        $this->country = $country;
    }

    public function index()
    {
        $countries = $this->country->getAllSuportedWithCities();
        return view('user::dukaan.profile.account-details.index',compact('countries'));
    }

    public function favourites()
    {
        $favourites = auth()->user()->favourites;
        return view('user::dukaan.profile.wishlist.index', compact('favourites'));
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        // check if user change mobile
        if (!is_null($request->mobile) && $request->mobile != auth()->user()->mobile) {
            $request->request->add(['mobile_verified_at' => null, 'firebase_id' => null]);
        }

        $update = $this->user->update($request, auth()->id());

        if ($update)
            return response()->json(['message' => __('user::frontend.profile.index.alert.success')]);

        return response()->json(['message' => __('user::frontend.profile.index.alert.error'),'errors' => []],422);
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $update = $this->user->updatePassword($request);

        if ($update)
            return response()->json(['message' => __('user::frontend.profile.index.alert.success')]);

        return response()->json(['message' => __('user::frontend.profile.index.alert.error'),'errors' => []],422);
    }

    public function addresses()
    {
        $addresses = AddressResource::collection($this->address->getAllByUsrId())->jsonSerialize();
        $countries = $this->country->getAllSuported();
        $cities = $this->country->getCitiesWithStatesByCountryId(currentCountry());

        return view('user::dukaan.profile.addresses.index', compact('addresses','countries','cities'));
    }

    public function createAddress()
    {
        return view('user::frontend.profile.addresses.create');
    }

    public function storeAddress(UpdateAddressRequest $request)
    {
        $this->setShippingTypeByRequest($request);
        $shippingValidateAddress = $this->shipping->validateAddress($request);

        if($shippingValidateAddress[0]){
            return Response()->json([false, 'errors' => ['state_id' => $shippingValidateAddress]],422);
        }else{
            $request->merge(['addressType' => $shippingValidateAddress['addressType'], 'jsonData' => $shippingValidateAddress['jsonData']]);
        }

        $update = $this->address->create($request);

        if ($update) {
            if ($request->state) {
                // Save user state for later operations
                set_cookie_value(config('core.config.constants.ORDER_STATE_ID'), $request->state);
                set_cookie_value(config('core.config.constants.ORDER_STATE_NAME'), $request->order_state_name);
            }
            $addresses = AddressResource::collection($this->address->getAllByUsrId())->jsonSerialize();
            return response()->json(['message' => __('user::frontend.profile.index.alert.success'),'addresses' => $addresses]);
        }

        return response()->json(['message' => __('user::frontend.profile.index.alert.error'),'errors' => []],422);
    }

    public function editAddress($countryPrefix,$id)
    {
        $address = $this->address->findById($id);
        return view('user::frontend.profile.addresses.address', compact('address'));
    }

    public function updateAddress(UpdateAddressRequest $request,$countryPrefix, $id)
    {
        $this->setShippingTypeByRequest($request);

        $shippingValidateAddress = $this->shipping->validateAddress($request);
        if($shippingValidateAddress[0]){
            return Response()->json([false, 'errors' => ['state_id' => $shippingValidateAddress]],400);
        }else{
            $request->merge(['addressType' => $shippingValidateAddress['addressType'], 'jsonData' => $shippingValidateAddress['jsonData']]);
        }

        $update = $this->address->update($request, $id);

        if ($update) {
            if ($request->state) {
                // Save user state for later operations
                set_cookie_value(config('core.config.constants.ORDER_STATE_ID'), $request->state);
                set_cookie_value(config('core.config.constants.ORDER_STATE_NAME'), $request->order_state_name);
            }
            $addresses = AddressResource::collection($this->address->getAllByUsrId())->jsonSerialize();
            return response()->json(['message' => __('user::frontend.profile.index.alert.success'),'addresses' => $addresses]);
        }

        return response()->json(['message' => __('user::frontend.profile.index.alert.error'),'errors' => []],422);
    }

    public function deleteAddress($countryPrefix,$id)
    {
        $update = $this->address->delete($id);

        if ($update){

            $addresses = AddressResource::collection($this->address->getAllByUsrId())->jsonSerialize();
            return response()->json(['message' => __('user::frontend.profile.index.alert.delete'),'addresses' => $addresses]);
        }
        return response()->json(['message' => __('user::frontend.profile.index.alert.error'),'errors' => []],422);
    }

    public function setAddressAsDefault($countryPrefix,$id)
    {
        $update = $this->address->setAddressAsDefault($id);

        if ($update){

            return response()->json(['message' => __('user::frontend.profile.index.alert.delete')]);
        }
        return response()->json(['message' => __('user::frontend.profile.index.alert.error'),'errors' => []],422);
    }

    public function deleteFavourite($countryPrefix,$prdId)
    {
        $favourite = $this->user->findFavourite(auth()->user()->id, $prdId);
        $check = $favourite->delete();

        if ($check)
            return redirect()->back()->with(['alert' => 'success', 'status' => __('user::frontend.favourites.index.alert.delete')]);;

        return redirect()->back()->with(['alert' => 'danger', 'status' => __('user::frontend.favourites.index.alert.error')]);
    }

    public function storeFavourite($countryPrefix,$prdId)
    {
        $toggle = auth()->user()->favourites()->toggle([$prdId]);
        return response()->json([
            'attached' => isset($toggle['attached']) && count($toggle['attached']),
            'productId' => $prdId,
        ], 200);

    }

}
