@extends('layouts.main')

@section('title', $site->qrcode ? '修改二维码' : '创建二维码')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <ul class="breadcrumb">
                <li><a href="{{route('dashboard.index')}}"><i class="fa fa-home"></i> 控制台</a></li>
                <li><a href="{{route('sites.index')}}"><i class="fa fa-cogs icon"></i> 展点管理</a></li>
                <li class="active">{{$site->qrcode ? '修改二维码' : '创建二维码'}}</li>
            </ul>
            @include('shared.alert')
        </div>

        <div class="col-lg-12">
            <form class="form-horizontal" data-validate="parsley" style="margin-bottom: 60px;" method="POST"
                  action="{{route('site.qrcode.store', ['site' => $site->id])}}">
                @csrf
                <section class="panel panel-default">
                    <header class="panel-heading"><strong>{{$site->qrcode ? '修改二维码' : '创建二维码'}}</strong></header>
                    <div class="panel-body">

                        <div class="form-group {{$errors->has('path') ? 'has-error' : ''}}">
                            <label class="col-sm-3 control-label">路径</label>
                            <div class="col-sm-9">
                                <input name="path" type="text" data-required="true" class="form-control"
                                       placeholder="小程序页面路径" value="">
                                @if($errors->has('path'))
                                    <span class="help-block">{{$errors->first('path')}}</span>
                                @endif
                            </div>
                        </div>
                        @if($site->qrcode)
                            <div class="form-group">
                                <label class="col-sm-3 control-label">当前二维码</label>
                                <div class="col-sm-9">
                                    <img style="border: 1px solid #888888;" src="{{$site->qrcode}}">
                                </div>
                            </div>
                        @endif

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