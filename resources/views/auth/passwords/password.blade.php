@extends('layouts.app')

@section('title', '重置密码')

@section('main')
    <header class="bg-dark dk header navbar navbar-fixed-top-xs">
        <div class="navbar-header aside-md">
            <a class="btn btn-link visible-xs" data-toggle="class:nav-off-screen" data-target="#nav">
                <i class="fa fa-bars"></i>
            </a>
            <a href="#" class="navbar-brand" data-toggle="fullscreen">
                <img src="{{ asset('images/logo.png') }}" class="m-r-sm">{{config('app.name')}}</a>
            <a class="btn btn-link visible-xs" data-toggle="dropdown"  data-target=".nav-user"> <i class="fa fa-cog"></i> </a>
        </div>
    </header>
    <div class="container" style="padding-top: 20px;">
        @include('shared.alert')
        <div class="row">
            @yield('content')
            {{--<div class="panel panel-default m-t-lg">--}}
                {{--<div class="wizard clearfix" id="form-wizard">--}}
                    {{--<ul class="steps">--}}
                        {{--<li data-target="#step1" class="active"><span class="badge badge-info">1</span>输入账号</li>--}}
                        {{--<li data-target="#step2"><span class="badge">2</span>获取验证码</li>--}}
                        {{--<li data-target="#step3"><span class="badge">3</span>重置密码</li>--}}
                    {{--</ul>--}}
                {{--</div>--}}
                {{--<div class="step-content">--}}
                    {{--<form id="password-form" data-validate="parsley" method="POST">--}}
                        {{--<div class="step-pane active" id="step1">--}}
                            {{--<p>登录账号（手机号码）：</p>--}}
                            {{--<input type="text" class="form-control" data-required="true" placeholder="手机号">--}}
                        {{--</div>--}}
                        {{--<div class="step-pane" id="step2">--}}
                            {{--<p>Your email:</p>--}}
                            {{--<input type="text" class="form-control" data-required="true" data-type="email"--}}
                                   {{--placeholder="email address">--}}
                        {{--</div>--}}
                        {{--<div class="step-pane" id="step3">This is step 3</div>--}}
                        {{--<div class="actions m-t">--}}
                            {{--<button type="submit"  class="btn btn-default btn-sm btn-next">下一步</button>--}}
                        {{--</div>--}}
                    {{--</form>--}}
                {{--</div>--}}
            {{--</div>--}}
        </div>
    </div>

@endsection