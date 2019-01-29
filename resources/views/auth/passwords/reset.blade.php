@extends('auth.passwords.password')

@section('content')
    <div class="panel panel-default m-t-lg">
        <div class="wizard clearfix" id="form-wizard">
            <ul class="steps">
                <li data-target="#step1"><span class="badge badge-success">1</span>输入账号</li>
                <li data-target="#step2" class=""><span class="badge badge-success">2</span>获取验证码</li>
                <li data-target="#step3"><span class="active badge">3</span>重置密码</li>
            </ul>
        </div>
        <div class="step-content">
            <form class="form-horizontal" id="password-form" data-validate="parsley" method="POST" action="{{ route('password.update') }}">
                @csrf
                <div class="step-pane active" id="step2">
                    <div class="form-group {{$errors->has('password') ? 'has-error' : ''}}">
                        <label class="col-lg-2 control-label text-right">密码：</label>
                        <div class="col-lg-10">
                            <input data-required="true" type="password" name="password" class="form-control " value="{{old('password')}}" placeholder="新密码">
                            @if($errors->has('password'))
                                <span class="help-block m-b-none">{{$errors->first('password')}}</span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 control-label text-right">重复密码：</label>
                        <div class="col-lg-10">
                            <input type="password" data-required="true" name="password_confirmation" class="form-control" value="{{old('password_confirmation')}}" placeholder="重复密码">
                        </div>
                    </div>
                </div>
                <div class="actions m-t">
                    <button type="submit" class="btn btn-default btn-sm btn-next">提交</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>



    </script>
@endsection