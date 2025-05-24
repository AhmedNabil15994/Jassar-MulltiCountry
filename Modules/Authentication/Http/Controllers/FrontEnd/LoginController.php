<?php

namespace Modules\Authentication\Http\Controllers\FrontEnd;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Authentication\Repositories\FrontEnd\AuthenticationRepository;
use Modules\Authentication\Traits\AuthenticationTrait;
use Modules\Catalog\Traits\ShoppingCartTrait;
use Modules\Authentication\Foundation\Authentication;
use Modules\Authentication\Http\Requests\FrontEnd\LoginRequest;

class LoginController extends Controller
{
    use Authentication, AuthenticationTrait, ShoppingCartTrait;

    protected $auth;

    public function __construct(AuthenticationRepository $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Display a listing of the resource.
     */
    public function showLogin(Request $request)
    {
        return view('authentication::dukaan.login', compact('request'));
    }

    /**
     * Login method
     */
    public function postLogin(LoginRequest $request)
    {
        $errors = $this->login($request);
        if ($errors)
            return response()->json(['message' => __('user::frontend.profile.index.alert.error'),'errors' => $errors],422);

        return response()->json();
    }


    /**
     * Logout method
     */
    public function logout(Request $request)
    {
        $this->clearCart();
        auth()->logout();
        return $this->redirectTo($request);
    }


    public function redirectTo($request)
    {
        if ($request['redirect_to'] == 'address')
            return redirect()->route('frontend.order.address.index');

        return redirect()->route('frontend.home');
    }

}
