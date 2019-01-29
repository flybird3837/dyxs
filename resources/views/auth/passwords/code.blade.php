@extends('auth.passwords.password')

@section('content')
    <div class="panel panel-default m-t-lg">
        <div class="wizard clearfix" id="form-wizard">
            <ul class="steps">
                <li data-target="#step1"><span class="badge badge-success">1</span>输入账号</li>
                <li data-target="#step2" class="active"><span class="badge badge-info">2</span>获取验证码</li>
                <li data-target="#step3"><span class="badge">3</span>重置密码</li>
            </ul>
        </div>
        <div class="step-content">
            <form id="password-form" data-validate="parsley" method="POST" action="{{ route('password.code.verify') }}">
                @csrf
                <div class="step-pane active" id="step2">
                    <div class="row" style="padding: 20px;">
                        <div class="col-xs-4 text-right">
                            手机号码：
                        </div>
                        <div class="col-xs-4 text-left" style="padding: 0;">
                            15869181957
                            <input type="hidden" name="phone" value="{{$phone}}">
                        </div>
                        <div class="col-xs-4">

                        </div>
                    </div>
                    <div class="row" style="padding:20px;display: flex;align-items: center">
                        <div class="col-xs-4 text-right">
                            验证码：
                        </div>
                        <div class="col-xs-2 text-left {{$errors->has('code') ? 'has-error': ''}}" style="padding: 0;">
                            <input data-required="true" name="code" placeholder="请输入验证码" type="text" value="{{old('code')}}"
                                   class="form-control">
                            @if($errors->has('code'))
                                <span class="help-block">{{$errors->first('code')}}</span>
                            @endif
                        </div>
                        <div class="col-xs-4">
                            <button data-url="{{route('password.code.send')}}" id="sendVerifyCodeSms" type="button"
                                    class="btn btn-rounded btn-danger"
                                    style="padding-left: 20px; padding-right: 20px;">点击获取验证码
                            </button>
                        </div>
                    </div>
                </div>
                <div class="actions m-t">
                    <button type="submit" class="btn btn-default btn-sm btn-next">下一步</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>

        var countDown;

        $('#sendVerifyCodeSms').on('click', function () {
            var url = $(this).data('url');
            var phone = $("input[name=phone]").val();

            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    phone: phone
                },
                success: function (res) {
                    alert('验证码发送成功，请查收');
                    afterVerifyCodeSent();
                },
                error: function (res) {
                    //console.log('fail', res)
                    var result = res.responseJSON;
                    alert('发送失败：' + result.errMsg);
                }
            })
        });

        function afterVerifyCodeSent() {
            var button = $('#sendVerifyCodeSms');
            var second = 60, msg;
            button.attr('disabled', true);
            countDown = setInterval(function () {
                if (second === 0) {
                    msg = '点击获取验证码';
                    button.text(msg);
                    button.removeAttr('disabled');
                    clearInterval(countDown);
                    return;
                }
                msg = '请在 ' + second + 's 后重试';
                button.text(msg);

                second--;
            }, 1000);
        }

    </script>
@endsection