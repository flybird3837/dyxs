<?php

namespace App\Http\Controllers\Traits;


use Carbon\Carbon;
use App\Http\Requests\Manage\Request;
use Illuminate\Validation\ValidationException;

trait SendsPasswordResetSms
{

    /**
     * 输入账号页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showUsernameForm()
    {
        session()->forget('current_reset_password_account');
        return view('auth.passwords.phone');
    }

    /**
     * 验证账号是否存在
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function verifyUsername(Request $request)
    {
        $this->validateUsername($request);

        $request->session()->put('current_reset_password_account', $request->phone);


        return redirect(route('password.code'));
    }

    /**
     * 显示获取验证码页面
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function showVerifyCodeForm(Request $request)
    {
        $phone = session('current_reset_password_account');

        if (!$phone) {
            $request->session()->flash('danger', '请先输入账号!');
            return redirect(route('password.username'));
        }

        return view('auth.passwords.code', [
            'phone' => $phone
        ]);
    }

    /**
     * 校验验证码
     *
     * @param Request $request
     * @return SendsPasswordResetSms|bool|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function verifyCode(Request $request)
    {
        if (($validation = $this->validateVerifyCode($request)) !== true) {
            return $validation->withInput();
        }

        //session()->forget('reset_password_verify_code');
        return redirect(route('password.reset'));

    }

    /**
     * 发送验证码
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendVerifyCode(Request $request)
    {
        $currentResetPasswordAccount = session('current_reset_password_account');

        $this->validate($request, [
            'phone' => "required",
        ]);

        if ($currentResetPasswordAccount != $request->phone) {
            throw ValidationException::withMessages([
                'phone' => '手机号码异常'
            ]);
        }

        $lastSendVerifyCodeTime = session('last_send_verify_code_time');
        if ($lastSendVerifyCodeTime && Carbon::now()->subMinute(1)->lessThan($lastSendVerifyCodeTime)) {
            return response()->json(['errMsg' => '请求太快啦'])->setStatusCode(500);
        }

        $verifyCode = str_random(6);

        session()->put('reset_password_verify_code', [
            'code' => $verifyCode,
            'expired_at' => Carbon::now()->addMinutes(10)
        ]);
        session()->put('last_send_verify_code_time', Carbon::now());

        return response()->json([
            'code' => $verifyCode
        ]);
    }


    protected function validateUsername(Request $request)
    {
        $this->validate($request,
            [
                'phone' => 'required|exists:users,phone'
            ],
            [
                'phone.required' => '账号不能为空',
                'phone.exists' => '账号错误'
            ]);
    }

    protected function validateVerifyCode(Request $request)
    {
        $currentResetPasswordAccount = session('current_reset_password_account');
        $this->validate($request,
            [
                'phone' => "required",
            ], [
                'phone.required' => '手机号码为空'
            ]);
        if ($currentResetPasswordAccount != $request->phone) {
            return back()->withErrors(['phone' => '手机号码异常']);
        }

        $resetPasswordVerifyCode = session('reset_password_verify_code');

        if (!$resetPasswordVerifyCode) {
            return back()->withErrors(['code' => '您还没有获取验证码或该验证码已经被使用']);
        }

        if ($request->code != $resetPasswordVerifyCode['code']) {
            return back()->withErrors(['code' => '验证码错误']);
        }

        if ($resetPasswordVerifyCode['expired_at']->lessThan(Carbon::now())) {
            return back()->withErrors(['code' => '验证码过期，请重新获取']);
        }

        return true;
    }

}