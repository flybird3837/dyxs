@extends('layouts.main')

@section('title', isset($map) ? '修改地图' : '添加地图')

@section('styles')
    <style>
        .selected-map {
            margin-top: 10px;
        }

        .selected-map img {
            width: 100%;
        }
    </style>

@endsection

@section('content')
    <div class="row">

        <div class="col-lg-12">
            <!-- .breadcrumb -->
            <ul class="breadcrumb">
                <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-home"></i> 控制台</a></li>
                <li><a href="{{ route('maps.index') }}"><i class="fa fa-indent icon"></i> 地图管理</a></li>
                <li class="active">{{isset($map) ? '修改地图' : '添加地图'}}</li>
            </ul>
            <!-- / .breadcrumb -->
            @include('shared.alert')
        </div>
        <div class="col-lg-12">
            <form class="form-horizontal" data-validate="parsley" style="margin-bottom: 60px;" method="POST"
                  action="{{isset($map) ? route('maps.update', ['map'=>$map->id]) : route('maps.store') }}"
                  enctype="multipart/form-data">
                @csrf
                @if(isset($map))
                    @method('PUT')
                @endif
                <section class="panel panel-default">
                    <header class="panel-heading"><strong>{{isset($map) ? '修改地图' : '添加地图'}}</strong></header>
                    <div class="panel-body">

                        <div class="form-group {{$errors->has('name') ? 'has-error' : ''}}">
                            <label class="col-sm-3 control-label">名称</label>
                            <div class="col-sm-9">
                                <input name="name" type="text" data-required="true" class="form-control"
                                       placeholder="地图名称" value="{{$map->name ?? old('name')}}">
                                @if($errors->has('name'))
                                    <span class="help-block">{{$errors->first('name')}}</span>
                                @endif
                            </div>
                        </div>
                        <div class="line line-dashed line-lg pull-in"></div>
                        <div class="form-group {{$errors->has('width') ? 'has-error' : ''}}">
                            <label class="col-sm-3 control-label">地图宽</label>
                            <div class="col-sm-9">
                                <input name="width" type="number" class="form-control"
                                       placeholder="地图宽度" value="{{$map->width ?? old('width')}}">
                                @if($errors->has('width'))
                                    <span class="help-block">{{$errors->first('width')}}</span>
                                @endif
                            </div>
                        </div>
                        <div class="line line-dashed line-lg pull-in"></div>
                        <div class="form-group {{$errors->has('height') ? 'has-error' : ''}}">
                            <label class="col-sm-3 control-label">地图高</label>
                            <div class="col-sm-9">
                                <input name="height" type="number" class="form-control"
                                       placeholder="地图宽度" value="{{$map->height ?? old('height')}}">
                                @if($errors->has('height'))
                                    <span class="help-block">{{$errors->first('height')}}</span>
                                @endif
                            </div>
                        </div>
                        <div class="line line-dashed line-lg pull-in"></div>
                        <div class="form-group {{$errors->has('floor') ? 'has-error' : ''}}">
                            <label class="col-sm-3 control-label">标注楼层</label>
                            <div class="col-sm-9">
                                <input name="floor" type="number" class="form-control"
                                       placeholder="地图楼层，没有就不填" value="{{$map->floor ?? old('floor')}}">
                                @if($errors->has('floor'))
                                    <span class="help-block">{{$errors->first('floor')}}</span>
                                @endif
                            </div>
                        </div>
                        <div class="line line-dashed line-lg pull-in"></div>
                        <div class="form-group {{$errors->has('file_id') ? 'has-error' : ''}}">
                            <label class="col-sm-3 control-label">平面图</label>
                            <div class="col-sm-9">
                                <div class="input-group m-b">
                                    <input name="file_id" type="text" class="form-control"
                                           value="{{$map->file_id ?? old('file_id')}}">
                                    <span class="input-group-addon" onclick="chooseFile('image', 'file_id')">选择</span>
                                </div>
                                @if($errors->has('file_id'))
                                    <span class="help-block">{{$errors->first('file_id')}}</span>
                                @endif
                                <div class="selected-map">
                                    <img id="file_id" src="{{$map->image->url ?? ''}}">
                                </div>
                            </div>
                        </div>
                        <div class="line line-dashed line-lg pull-in"></div>
                        <div class="form-group {{$errors->has('thumb_file_id') ? 'has-error' : ''}}">
                            <label class="col-sm-3 control-label">缩略图</label>
                            <div class="col-sm-9">
                                <div class="input-group m-b">
                                    <input name="thumb_file_id" type="text" class="form-control"
                                           value="{{$map->thumb_file_id ?? old('thumb_file_id')}}">
                                    <span class="input-group-addon" onclick="chooseFile('image', 'thumb_file_id')">选择</span>
                                </div>
                                @if($errors->has('thumb_file_id'))
                                    <span class="help-block">{{$errors->first('thumb_file_id')}}</span>
                                @endif
                                <div class="selected-map">
                                    <img id="thumb_file_id" src="{{$map->thumbImage->url ?? ''}}">
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

@section('scripts')
    <script>
        function chooseFile(type, field) {
            {{--console.log('{{config('app.url')}}/medium/select?type=' + type + '&field=' + field);--}}
            {{--return ;--}}
            layer.open({
                type: 2,
                area: ['90%', '90%'],
                fixed: false, //不固定
                maxmin: true,
                content: '{{config('app.url')}}/medium/select?type=' + type + '&field=' + field
            })
        }

        function choosedFiles(files, type, field) {
            if (field === 'file_id') {
                $("input[name=file_id]").val(files[0].id);
                $("#file_id").attr('src', files[0].url);
            }

            if (field === 'thumb_file_id') {
                $("input[name=thumb_file_id]").val(files[0].id);
                $("#thumb_file_id").attr('src', files[0].url);
            }

        }
    </script>
@endsection