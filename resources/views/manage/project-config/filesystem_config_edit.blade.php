@extends('layouts.main')

@section('title', '文件系统配置')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <ul class="breadcrumb">
                <li><a href="{{route('dashboard.index')}}"><i class="fa fa-home"></i> 控制台</a></li>
                <li><a href="javascript:void(0)"><i class="fa fa-cogs icon"></i> 系统设置</a></li>
                <li class="active">文件系统配置</li>
            </ul>
            @include('shared.alert')
        </div>

        <div class="col-lg-12">
            <form class="form-horizontal" data-validate="parsley" style="margin-bottom: 60px;" method="POST"
                  action="{{route('config.filesystem.update')}}">
                @csrf

                <section class="panel panel-default">
                    <header class="panel-heading"><strong>文件系统配置</strong></header>
                    <div class="panel-body">

                        <div class="form-group {{$errors->has('disk')?'has-error':''}}">
                            <label class="col-sm-3 control-label">存储磁盘</label>
                            <div class="col-sm-9">
                                <div class="m-b">
                                    <select name="disk" id="select2-option" style="width:260px">
                                        @foreach($disks as $disk)
                                            <option @if(!empty($config['disk']) && $config['disk'] == $disk) selected
                                                    @endif value="{{$disk}}">{{$disk}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="line line-dashed line-lg pull-in"></div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">腾讯云配置信息</label>
                            <div class="col-sm-9">
                                <div style="width: 80%; margin-left: 20px;">
                                    <input type="hidden" name="qcloud-cos[driver]" value="qcloud-cos">
                                    <div class="form-group">
                                        <label>地域</label>
                                        <input name="qcloud-cos[region]" class="form-control"
                                               value="{{$config['qcloud-cos']['region'] ?? ''}}">
                                    </div>
                                    <div class="form-group">
                                        <label>appId</label>
                                        <input name="qcloud-cos[credentials][app_id]" class="form-control"
                                               value="{{$config['qcloud-cos']['credentials']['app_id'] ?? ''}}">
                                    </div>
                                    <div class="form-group">
                                        <label>secretId</label>
                                        <input name="qcloud-cos[credentials][secret_id]" class="form-control"
                                               value="{{$config['qcloud-cos']['credentials']['secret_id'] ?? ''}}">
                                    </div>
                                    <div class="form-group">
                                        <label>secretKey</label>
                                        <input name="qcloud-cos[credentials][secret_key]" class="form-control"
                                               placeholder=""
                                               value="{{$config['qcloud-cos']['credentials']['secret_key'] ?? ''}}">
                                    </div>
                                    <div class="form-group">
                                        <label>默认存储桶</label>
                                        <input name="qcloud-cos[default_bucket]" class="form-control" placeholder=""
                                               value="{{$config['qcloud-cos']['default_bucket'] ?? ''}}">
                                    </div>
                                    <div class="form-group">
                                        <label>业务超时</label>
                                        <input name="qcloud-cos[timeout]" class="form-control" placeholder=""
                                               value="{{$config['qcloud-cos']['timeout'] ?? 3600}}">
                                    </div>
                                    <div class="form-group">
                                        <label>链接超时</label>
                                        <input name="qcloud-cos[connect_timeout]" class="form-control" placeholder=""
                                               value="{{$config['qcloud-cos']['connect_timeout'] ?? 3600}}">
                                    </div>
                                </div>
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