@extends('auth.passwords.password')

@section('content')
    <div class="panel panel-default">
        <div class="wizard clearfix" id="form-wizard">
            <ul class="steps">
                <li data-target="#step1" class="active"><span class="badge badge-info">1</span>输入账号</li>
                <li data-target="#step2"><span class="badge">2</span>获取验证码</li>
                <li data-target="#step3"><span class="badge">3</span>重置密码</li>
            </ul>
        </div>
        <div class="step-content">
            <form id="password-form" data-validate="parsley" method="POST"
                  action="{{ route('password.username.verify') }}">
                @csrf
                <div class="step-pane active {{$errors->has('phone') ? ' has-error' : ''}}" id="step1">
                    <p>登录账号（手机号码）：</p>
                    <input name="phone" type="text" class="form-control" data-required="true" placeholder="手机号">
                    @if($errors->has('phone'))
                        <span class="help-block">{{$errors->first('phone')}}</span>
                    @endif
                </div>
                <div class="actions m-t">
                    <button type="submit" class="btn btn-default btn-sm btn-next">下一步</button>
                </div>
            </form>
        </div>
    </div>
@endsection