<?php

namespace Modules\Authentication\Http\Controllers\FrontEnd;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Authentication\Foundation\Authentication;
use Modules\Authentication\Http\Requests\FrontEnd\ResetPasswordRequest;
use Modules\Authentication\Repositories\FrontEnd\AuthenticationRepository as AuthenticationRepo;
use Modules\User\Entities\PasswordReset;

class ResetPasswordController extends Controller
{
    use Authentication;

    function __construct(AuthenticationRepo $auth)
    {
        $this->auth = $auth;
    }

    public function resetPassword($countryPrefix,$token)
    {
        $email = request('email');
        abort_unless(PasswordReset::where('token', $token)->first(), 419);
        abort_unless(PasswordReset::where([
            'token' => $token,
            'email' => $email,
        ])->first(), 419);

        return view('authentication::dukaan.reset-password', compact('token','email'));
    }


    public function updatePassword(ResetPasswordRequest $request)
    {
        abort_unless(PasswordReset::where('token', $request->token)->first(), 419);
        abort_unless(PasswordReset::where([
            'token' => $request->token,
            'email' => $request->email,
        ])->first(), 419);

        $reset = $this->auth->resetPassword($request);
        $errors = $this->login($request);
        if ($errors)
            return response()->json(['message' => __('user::frontend.profile.index.alert.error'),'errors' => $errors],422);

            return response()->json(['message' => 'Password Reset Successfully']);
    }
}
