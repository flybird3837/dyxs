<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manage\Request;
use App\Models\User;
use Carbon\Carbon;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    //use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showResetForm(Request $request, $token = null)
    {
        $phone = session('current_reset_password_account');
        $verifyCode = session('reset_password_verify_code');

        if (!$phone || !$verifyCode) {
            return redirect(route('password.username'));
        }

        return view('auth.passwords.reset');
    }

    public function reset(Request $request)
    {

        $this->validate($request, [
            'password' => 'required|min:6|confirmed'
        ]);

        $phone = session('current_reset_password_account');
        $verifyCode = session('reset_password_verify_code');


        if ($verifyCode['expired_at']->lessThan(Carbon::now())) {
            $request->session()->flash('danger', '验证码过期');
            return redirect(route('password.username'));
        }

        $user = User::where('phone', $phone)->first();
        if (!$user) {
            $request->session()->flash('danger', '该用户不存在');
            return redirect(route('password.username'));
        }

        $user->password = $request->password;
        $user->save();

        return redirect($this->redirectTo);

    }

}
