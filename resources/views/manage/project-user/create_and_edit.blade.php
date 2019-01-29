@extends('layouts.main')
@section('title', isset($user) ? '修改党组织'.$role_name:'添加党组织'.$role_name)
@section('content-header', isset($user) ? '修改党组织'.$role_name:'添加党组织'.$role_name)
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <ul class="breadcrumb">
                <li><a href="{{route('dashboard.index')}}"><i class="fa fa-home"></i> 控制台</a></li>
                <li><a href="{{route('projects.index')}}"><i class="fa fa-list-ul"></i> 党组织管理</a></li>
                <li class="active">{{isset($user) ? '修改党组织'.$role_name:'添加党组织'.$role_name}}</li>
            </ul>
            @include('shared.alert')
        </div>

        <div class="col-lg-12">
            <form class="form-horizontal" data-validate="parsley" style="margin-bottom: 60px;" method="POST"
                  action="{{ isset($user) ? route('projects.user.update', ['project' => $project->id, 'user' => $user->id]) : route('projects.user.store', ['project' => $project->id])}}">
                @csrf
                @if(isset($user))
                    @method('PUT')
                @endif
                <input type="hidden" name="role" value="{{$role}}"/>
                <section class="panel panel-default">
                    <header class="panel-heading"><strong>{{isset($user) ? '修改党组织'.$role_name:'添加党组织'.$role_name}}</strong></header>
                    <div class="panel-body">

                        <div class="form-group {{$errors->has('name') ? 'has-error': ''}}">
                            <label class="col-sm-3 control-label">{{$role_name}}名称</label>
                            <div class="col-sm-9">
                                <input name="name" type="text" class="form-control" placeholder="{{$role_name}}名称，不填写默认生成"
                                       value="{{isset($user) ? $user->name : old('name')}}">
                                @if($errors->has('name'))
                                    <span class="help-block">{{$errors->first('name')}}</span>
                                @endif
                            </div>
                        </div>
                        <div class="line line-dashed line-lg pull-in"></div>
                        <div class="form-group {{$errors->has('phone') ? 'has-error': ''}}">
                            <label class="col-sm-3 control-label">手机号码</label>
                            <div class="col-sm-9">
                                <input name="phone" type="text" data-required="true" class="form-control"
                                       placeholder="手机号码作为登录账号，不能为空"
                                       value="{{ isset($user) ? $user->phone : old('phone')}}">
                                @if($errors->has('phone'))
                                    <span class="help-block">{{$errors->first('phone')}}</span>
                                @endif
                            </div>
                        </div>
                        <div class="line line-dashed line-lg pull-in"></div>
                        <div class="form-group {{$errors->has('password') ? 'has-error': ''}}">
                            <label class="col-sm-3 control-label">密码</label>
                            <div class="col-sm-9">
                                <div class="input-group m-b">
                                    <input type="password" name="password" class="form-control"
                                           placeholder="{{isset($user) ? '不修改则留空' : '密码为空则默认 123456'}}"
                                           value="{{old('password')}}">
                                    <span class="input-group-addon"><i class="fa fa-eye"></i></span>
                                </div>
                                @if($errors->has('password'))
                                    <span class="help-block">{{$errors->first('password')}}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <footer class="panel-footer text-right bg-light lter">
                        <button type="button" onclick="javascript:window.history.back();"
                                class="btn btn-default btn-s-xs pull-left">返回
                        </button>
                        <button type="submit" class="btn btn-success btn-s-xs">提交</button>
                    </footer>
                </section>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $('.input-group-addon').on('click', function () {
            var icon = $(this).children('i'), $this = $(this);
            if (icon.hasClass('fa-eye')) {
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
                $this.siblings('input').attr('type', 'text');
            } else {
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
                $this.siblings('input').attr('type', 'password');
            }
        })
    </script>
@endsection