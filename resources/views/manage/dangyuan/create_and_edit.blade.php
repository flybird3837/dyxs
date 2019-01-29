@extends('layouts.main')

@section('title', isset($device) ? '修改设备' : '新增设备')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <ul class="breadcrumb">
                <li><a href="{{route('dashboard.index')}}"><i class="fa fa-home"></i> 控制台</a></li>
                <li><a href="{{route('devices.index')}}"><i class="fa fa-cogs icon"></i> 设备管理</a></li>
                <li class="active">{{isset($device) ? '修改设备' : '新增设备'}}</li>
            </ul>
            @include('shared.alert')
        </div>

        <div class="col-lg-12">
            <form class="form-horizontal" data-validate="parsley" style="margin-bottom: 60px;" method="POST"
                  action="{{ isset($device) ? route('devices.update', ['device' => $device->id]) : route('devices.store') }}">
                @csrf
                @if(!empty($device))
                    @method('PUT')
                @endif
                <section class="panel panel-default">
                    <header class="panel-heading"><strong>{{isset($device) ? '修改设备' : '新增设备'}}</strong></header>
                    <div class="panel-body">

                        <div class="form-group {{$errors->has('uuid') ? 'has-error' : ''}}">
                            <label class="col-sm-3 control-label">设备 uuid</label>
                            <div class="col-sm-9">
                                <input name="uuid" type="text" data-required="true" class="form-control"
                                       placeholder="设备 uuid" value="{{isset($device) ? $device->uuid : old('uuid')}}">
                                @if($errors->has('uuid'))
                                    <span class="help-block">{{$errors->first('uuid')}}</span>
                                @endif
                            </div>
                        </div>
                        <div class="line line-dashed line-lg pull-in"></div>
                        <div class="form-group {{$errors->has('name') ? 'has-error' : ''}}">
                            <label class="col-sm-3 control-label">设备名称</label>
                            <div class="col-sm-9">
                                <input name="name" type="text" class="form-control" placeholder="设备名称，用于识别设备"
                                       value="{{isset($device->name) ? $device->name : old('name')}}">
                                @if($errors->has('name'))
                                    <span class="help-block">{{$errors->first('name')}}</span>
                                @endif
                            </div>
                        </div>
                        <div class="line line-dashed line-lg pull-in"></div>
                        <div class="form-group {{($errors->has('x') || $errors->has('y') || $errors->has('z')) ? 'has-error' : ''}}">
                            <label class="col-sm-3 control-label">设备位置</label>
                            <div class="col-sm-9">
                                <div class="row">
                                    <div class="col-md-2">
                                        <input name="x" type="text" class="form-control" placeholder="x"
                                               value="{{isset($device->x) ? $device->x : old('x')}}">
                                        @if($errors->has('x'))
                                            <span class="help-block">{{$errors->first('x')}}</span>
                                        @endif
                                    </div>
                                    <div class="col-md-2">
                                        <input name="y" type="text" class="form-control" placeholder="y"
                                               value="{{isset($device->y)? $device->y : old('y')}}">
                                        @if($errors->has('y'))
                                            <span class="help-block">{{$errors->first('y')}}</span>
                                        @endif
                                    </div>
                                    <div class="col-md-2">
                                        <input name="z" type="text" class="form-control" placeholder="z"
                                               value="{{$device->z ?? old('z')}}">
                                        @if($errors->has('z'))
                                            <span class="help-block">{{$errors->first('z')}}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="line line-dashed line-lg pull-in"></div>
                        <div class="form-group {{$errors->has('site_id')?'has-error':''}}">
                            <label class="col-sm-3 control-label">关联展点</label>
                            <div class="col-sm-9">
                                <div class="m-b">
                                    <select name="site_id" id="select2-option" style="width:260px">
                                        <option value="" @if(empty($device->site_id)) selected @endif>暂不选择</option>
                                        @foreach($sites as $site)
                                            <option value="{{$site->id}}"
                                                    @if(!empty($device) && $device->site_id == $site->id) selected @endif>{{$site->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <footer class="panel-footer text-right bg-light lter">
                        <button type="button" onclick="javascript:window.history.back();"
                                class="btn btn-default btn-s-xs pull-left">返回
                            <button type="submit" class="btn btn-success btn-s-xs">提交</button>
                    </footer>
                </section>
            </form>
        </div>
    </div>
@endsection