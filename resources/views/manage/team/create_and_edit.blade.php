@extends('layouts.main')
@section('title', isset($project) ? '修改集体照' : '添加集体照')
@section('content-header', isset($project) ? '修改集体照' : '添加集体照')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <ul class="breadcrumb">
                <li><a href="{{route('dashboard.index')}}"><i class="fa fa-home"></i> 控制台</a></li>
                <li><a href="{{route('projects.index')}}"><i class="fa fa-list-ul"></i> 集体照管理</a></li>
                <li class="active">{{isset($project) ? '修改集体照' : '添加集体照'}}</li>
            </ul>
            @include('shared.alert')
        </div>

        <div class="col-lg-12">
            <form class="form-horizontal" data-validate="parsley" style="margin-bottom: 60px;" method="POST"
                  action="{{ isset($project) ? route('projects.update', ['project' => $project->id]) : route('projects.store') }}" enctype="multipart/form-data" onsubmit="return check()">
                @csrf
                @if(isset($project))
                    @method('PUT')
                @endif
                <section class="panel panel-default">
                    <header class="panel-heading"><strong>{{isset($project) ? '修改集体照' : '添加集体照'}}</strong></header>
                    <div class="panel-body">

                        <div class="form-group {{$errors->has('name') ? 'has-error' : ''}}">
                            <label class="col-sm-3 control-label">集体照名称</label>
                            <div class="col-sm-9">
                                <input name="name" type="text" class="form-control" placeholder="党组织名称"
                                       value="{{isset($project) ? $project->name : old('name')}}">
                                @if($errors->has('name'))
                                    <span class="help-block">{{$errors->first('name')}}</span>
                                @endif
                            </div>
                        </div>
                        <div class="line line-dashed line-lg pull-in"></div>
                        <div class="form-group {{$errors->has('description') ? 'has-error' : ''}}">
                            <label for="description" class="col-sm-3 control-label">LOGO</label>
                            <div class="col-sm-9">
                                <img src="{{$project->image}}" style="width:100px;height:auto"/>
                                <input id="image" name="image"
                                          class="form-control" type="file"/>
                                @if($errors->has('description'))
                                    <span class="help-block">{{$errors->first('description')}}</span>
                                @endif
                            </div>
                        </div>
                        <div class="line line-dashed line-lg pull-in"></div>
                        <div class="form-group {{$errors->has('wechat_mini_program_id') ? 'has-error' : ''}}">
                            <label class="col-sm-3 control-label">新密码</label>
                            <div class="col-sm-9">
                                <div class="input-group m-b">
                                    <input id="password" name="password" type="password" class="form-control"
                                           placeholder="新密码"
                                           value="">
                                    <span class="input-group-addon"><i class="fa fa-eye"></i></span>
                                </div>
                                @if($errors->has('wechat_mini_program_id'))
                                    <span class="help-block">{{$errors->first('wechat_mini_program_id')}}</span>
                                @endif
                            </div>
                        </div>
                        <div class="line line-dashed line-lg pull-in"></div>
                        <div class="form-group {{$errors->has('wechat_mini_program_secret') ? 'has-error' : ''}}">
                            <label class="col-sm-3 control-label">确认密码</label>
                            <div class="col-sm-9">
                                <div class="input-group m-b">
                                    <input id="re_password" name="re_password" type="password" class="form-control"
                                           placeholder="确认密码"
                                           value="">
                                    <span class="input-group-addon"><i class="fa fa-eye"></i></span>
                                </div>
                                @if($errors->has('wechat_mini_program_id'))
                                    <span class="help-block">{{$errors->first('wechat_mini_program_secret')}}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <footer class="panel-footer text-center bg-light lter">
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

        function check(){
            if($('#password').val()!='' && $('#password').val()!=$('#re_password').val()){
                alert('新密码与确认密码不一致请重新输入');
                return false;
            }
            return true;
        }
    </script>
@endsection