@extends('layouts.app')

@section('html-bg-color-class', 'bg-dark')

@section('title', '登录')

@section('main')

    <section id="content" class="m-t-lg wrapper-md animated fadeInUp">
        <div class="container aside-xxl"><a class="navbar-brand block" href="javascript:void(0)">{{ config('app.name') }}</a>
            <section class="panel panel-default bg-white m-t-lg">
                <header class="panel-heading text-center"><strong>登录</strong></header>

                <form  action="{{ route('login') }}" aria-label="登录" class="panel-body wrapper-lg" method="POST" data-validate="parsley">
                    @csrf
                    <div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }}">
                        <label class="control-label">账号（手机号码）</label>
                        <input value="{{old('phone')}}" id="phone" type="text" placeholder="手机号码" class="form-control input-lg" name="phone" data-required="true">
                        @if($errors->has('phone'))
                            <span class="help-block">{{ $errors->first('phone') }}</span>
                        @endif
                    </div>
                    <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                        <label class="control-label">密码</label>
                        <input type="password" id="password" placeholder="密码" class="form-control input-lg" name="password" data-required="true">
                        @if($errors->has('password'))
                            <span class="help-block">{{ $errors->first('password') }}</span>
                        @endif
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="remember"/>
                            记住我</label>
                    </div>
                    <a href="#" class="pull-right m-t-xs">
                        <small>忘记密码？</small>
                    </a>
                    <button type="submit" class="btn btn-primary">登录</button>
                </form>
            </section>
        </div>
    </section>
    <!-- footer -->
    <footer id="footer">
        <div class="text-center padder">
            <p>
                <small>杭州久未网络科技有限责任公司<br>
                    &copy; 2018 - 2020
                </small>
            </p>
        </div>
    </footer>
@endsection
