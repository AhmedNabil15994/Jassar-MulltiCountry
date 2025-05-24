<?php

namespace Modules\Authentication\Http\Controllers\FrontEnd;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Authentication\Foundation\Authentication;
use Modules\Authentication\Http\Requests\FrontEnd\RegisterRequest;
use Modules\Authentication\Repositories\FrontEnd\AuthenticationRepository as AuthenticationRepo;
use Modules\Area\Repositories\FrontEnd\CountryRepository;
use Modules\Authentication\Traits\AuthenticationTrait;
use Modules\Catalog\Traits\ShoppingCartTrait;

class RegisterController extends Controller
{
    use Authentication, ShoppingCartTrait, AuthenticationTrait;

    protected $country;
    protected $auth;

    public function __construct(AuthenticationRepo $auth, CountryRepository $country)
    {
        $this->auth = $auth;
        $this->country = $country;
    }

    public function show()
    {
        $countries = $this->country->getAllSuportedWithCities();
        return view('authentication::dukaan.signup',compact('countries'));
    }

    public function register(RegisterRequest $request)
    {
        $registered = $this->auth->register($request);
        if ($registered) {
            $this->loginAfterRegister($request);
            if (isset($request->type) && $request->type == 'checkout') {
                $this->removeCartConditionByType('company_delivery_fees', get_cookie_value(config('core.config.constants.CART_KEY')));
                $this->updateCartKey(get_cookie_value(config('core.config.constants.CART_KEY')), $registered->id);
                return redirect()->route('frontend.checkout.index');
            }
            return response()->json();
        } else
            return response()->json(['message' => 'try again','errors' => []],422);
    }

}
