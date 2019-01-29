@extends('layouts.main')

@section('styles')
    <style>
        .selected-files {
            margin-top: 15px;
            display: flex;
        }

        .selected-file {
            width: 100px;
            height: 100px;
            overflow: hidden;
            border: 1px solid #f5f5f5;
            padding: 5px;
            border-radius: 3px;
            margin-right: 5px;
        }

        .selected-file img {
            width: 100%;
        }

        .selected-audios {
            margin-top: 15px;
        }

        .selected-audios .audio-item {
            display: flex;
            align-items: center;
            margin-top: 5px;
        }

        .selected-audios .audio-item .audio-name {
            margin-left: 10px;
            font-size: 16px;
        }

        .selected-videos {
            margin-top: 15px;
            display: flex;
        }

        .selected-videos .video-item {
            border: 1px solid #f5f5f5;
            height: 30px;
            display: flex;
            align-items: center;
            padding: 5px;
            margin-right: 10px;
        }

        .point-item {
            margin-top: 10px;
        }

        .point-item:first-child {
            margin-top: 0;
        }

    </style>
@endsection
@push('links')
    <link href="{{ asset('js/summernote/summernote.css') }}" rel="stylesheet">
@endpush
@section('title', isset($site) ? '修改展点' : '新增展点')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <ul class="breadcrumb">
                <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-home"></i> 控制台</a></li>
                <li><a href="{{ route('sites.index') }}"><i class="fa fa-list-ul"></i> 展点管理</a></li>
                <li class="active">{{isset($site) ? '修改展点' : '新增展点'}}</li>
            </ul>
            @include('shared.alert')
        </div>

        <div class="col-lg-12">
            <form class="form-horizontal" data-validate="parsleys" style="margin-bottom: 60px;" method="POST"
                  action="{{ isset($site) ? route('sites.update', ['site'=>$site->id]) : route('sites.store') }}"
                  enctype="multipart/form-data">
                @csrf
                @if(isset($site))
                    @method('PUT')
                @endif
                <section class="panel panel-default">
                    <header class="panel-heading"><strong>新增展点</strong></header>
                    <div class="panel-body">
                        <div class="form-group {{$errors->has('map_id')?'has-error':''}}">
                            <label class="col-sm-3 control-label">所属地图</label>
                            <div class="col-sm-9">
                                <div class="m-b">
                                    <select name="map_id" id="select2-option" style="width:260px">
                                        @foreach($maps as $map)
                                            <option value="{{$map->id}}"
                                                    @if(!empty($site) && $site->map_id == $map->id) selected @endif>{{$map->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="line line-dashed line-lg pull-in"></div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">VIP展点</label>
                            <div class="col-sm-9">
                                <div class="form-group" style="margin-left:5px"> 
                                    <label class="radio-inline">
                                        <input name="isvip" type="radio" value="1" @if(isset($site) && $site->isvip == 1) checked @endif/>是
                                    </label>
                                    <label class="radio-inline">
                                        <input name="isvip" type="radio" value="0" @if(!isset($site)) checked @endif @if(isset($site) && $site->isvip == 0) checked @endif/>否
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="line line-dashed line-lg pull-in"></div>
                        <div class="form-group {{$errors->has('type') ? 'has-error' : ''}}">
                            <label class="col-sm-3 control-label">展点类型类型</label>
                            <div class="col-sm-9">
                                <select name="type" class="form-control">
                                    <option value="0">空类型</option>
                                    <option value="1" @if(isset($site->type) && $site->type == 1) selected @endif>音频</option>
                                    <option value="2" @if(isset($site) && $site->type == 2) selected @endif>视频</option>
                                </select>
                                @if($errors->has('type'))
                                    <span class="help-block">{{$errors->first('type')}}</span>
                                @endif
                            </div>
                        </div>
                        <div class="line line-dashed line-lg pull-in"></div>
                        <div class="form-group {{$errors->has('name')?'has-error':''}}">
                            <label class="col-sm-3 control-label">展点名称</label>
                            <div class="col-sm-9">
                                <input name="name" type="text" data-required="true" class="form-control"
                                       placeholder="展点名称" value="{{$site->name ?? old('name')}}">
                                @if($errors->has('name'))
                                    <span class="help-block">{{$errors->first('name')}}</span>
                                @endif
                            </div>
                        </div>
                        <div class="line line-dashed line-lg pull-in"></div>
                        <div class="form-group {{$errors->has('accuracy')?'has-error':''}}">
                            <label class="col-sm-3 control-label">精度</label>
                            <div class="col-sm-9">
                                <input name="accuracy" type="text" class="form-control"
                                       value="{{$site->accuracy ?? old('accuracy')}}">
                                @if($errors->has('accuracy'))
                                    <span class="help-block">{{$errors->first('accuracy')}}</span>
                                @endif
                            </div>
                        </div>
                        <div class="line line-dashed line-lg pull-in"></div>
                        <div class="form-group ">
                            <label class="col-sm-3 control-label">中心点</label>
                            <div class="col-sm-9">
                                <div class="row">
                                    <div class="col-md-2 {{$errors->has('center_point.x')?'has-error':''}}">
                                        <input name="center_point[x]" type="text" class="form-control" placeholder="x"
                                               value="{{$site->center_point['x'] ?? old('center_point')['x']}}">
                                        @if($errors->has('center_point.x'))
                                            <span class="help-block">{{$errors->first('center_point.x')}}</span>
                                        @endif
                                    </div>
                                    <div class="col-md-2 {{$errors->has('center_point.y')?'has-error':''}}">
                                        <input name="center_point[y]" type="text" class="form-control" placeholder="y"
                                               value="{{$site->center_point['y'] ?? old('center_point')['y']}}">
                                        @if($errors->has('center_point.y'))
                                            <span class="help-block">{{$errors->first('center_point.y')}}</span>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="line line-dashed line-lg pull-in"></div>
                        <div class="form-group {{$errors->has('points')?'has-error':''}}">
                            <label class="col-sm-3 control-label">展点区域坐标点</label>
                            <div class="col-sm-9 points">
                                @if(!empty($site->points))
                                    @foreach($site->points as $point)
                                        <div class="row point-item">
                                            <div class="col-md-2">
                                                <input name="points[x][]" type="text" class="form-control"
                                                       placeholder="x" value="{{$point['x']}}">
                                            </div>
                                            <div class="col-md-2">
                                                <input name="points[y][]" type="text" class="form-control"
                                                       placeholder="y" value="{{$point['y']}}">
                                            </div>

                                            <button type="button" onclick="deletePoint(this)"
                                                    class="btn btn-rounded btn-sm btn-icon btn-default"><i
                                                        class="fa fa-times"></i></button>

                                        </div>
                                    @endforeach
                                @elseif(old('points'))
                                    @for($i=0; $i<count(old('points')['x']); $i++)
                                        <div class="row point-item">
                                            <div class="col-md-2">
                                                <input name="points[x][]" type="text" class="form-control"
                                                       placeholder="x" value="{{old('points')['x'][$i]}}">
                                            </div>
                                            <div class="col-md-2">
                                                <input name="points[y][]" type="text" class="form-control"
                                                       placeholder="y" value="{{old('points')['y'][$i]}}">
                                            </div>
                                            <button type="button" onclick="deletePoint(this)"
                                                    class="btn btn-rounded btn-sm btn-icon btn-default"><i
                                                        class="fa fa-times"></i></button>
                                        </div>
                                    @endfor
                                @else
                                    {{--<div class="row point-item">--}}
                                        {{--<div class="col-md-2">--}}
                                            {{--<input name="points[x][]" type="text" class="form-control" placeholder="x">--}}
                                        {{--</div>--}}
                                        {{--<div class="col-md-2">--}}
                                            {{--<input name="points[y][]" type="text" class="form-control" placeholder="y">--}}
                                        {{--</div>--}}
                                        {{--<button type="button" onclick="deletePoint(this)"--}}
                                                {{--class="btn btn-rounded btn-sm btn-icon btn-default"><i--}}
                                                    {{--class="fa fa-times"></i></button>--}}
                                    {{--</div>--}}
                                @endif

                                @if($errors->has('points'))
                                    <span class="help-block">{{$errors->first('points')}}</span>
                                @endif

                                <button type="button" onclick="addPoint(this)"
                                        class="btn btn-s-md btn-default point-item">添加坐标点
                                </button>
                            </div>
                        </div>
                        <div class="line line-dashed line-lg pull-in"></div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">图片</label>
                            <div class="col-sm-9">
                                <a onclick="chooseFile('image')" href="javascript:void(0);"
                                   class="btn btn-s-md btn-default">选择图片</a>

                                <input type="hidden" name="images"
                                       value="{{!empty($site->images) ? implode('|', $site->images) : ''}}"/>

                                <div class="selected-files selected-images">
                                    @if(!empty($site->images))
                                        @foreach($site->imagesFile as $image)
                                            <div class="selected-file">
                                                <img src="{{$image->url}}">
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                        </div>

                        <div class="line line-dashed line-lg pull-in"></div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">音频</label>
                            <div class="col-sm-9">
                                <a onclick="chooseFile('audio')" href="javascript:void(0);"
                                   class="btn btn-s-md btn-default">选择音频</a>
                                <input type="hidden" name="audios"
                                       value="{{!empty($site->audios) ? implode('|', $site->audios) : ''}}"/>

                                <div class="selected-audios">
                                    @if(!empty($site->audios))
                                        @foreach($site->audiosFile as $audio)
                                            <div class="audio-item">
                                                <audio controls src="{{$audio->url}}">
                                                    您的浏览器不支持 audio 标签。
                                                </audio>
                                                <div class="audio-name">
                                                    {{$audio->client_origin_name}}
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="line line-dashed line-lg pull-in"></div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">视频</label>
                            <div class="col-sm-9">
                                <a onclick="chooseFile('video')" href="javascript:void(0);"
                                   class="btn btn-s-md btn-default">选择视频</a>
                                <input type="hidden" name="videos"
                                       value="{{!empty($site->videos) ? implode('|', $site->videos) : ''}}">
                                <div class="selected-videos">
                                    @if(!empty($site->videos))
                                        @foreach($site->videosFile as $video)
                                            <div class="video-item">
                                                <a data-href="{{$video->url}}" href="javascript:void(0)">
                                                    {{$video->client_origin_name}}

                                                </a>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="line line-dashed line-lg pull-in"></div>
                        <div class="form-group {{$errors->has('descriptions')?'has-error':''}}">
                            <label for="description" class="col-sm-3 control-label">展点描述</label>
                            <div class="col-sm-9">
                                <textarea id="descriptions"
                                          name="descriptions">{!! $site->descriptions ?? old('descriptions') !!}</textarea>
                                @if($errors->has('description'))
                                    <span class="help-block">{{$errors->first('descriptions')}}</span>
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

@push('scripts')
    <script src="{{ asset('js/summernote/summernote.min.js') }}"></script>
    <script src="{{ asset('js/summernote/lang/summernote-zh-CN.js') }}"></script>
@endpush


@section('scripts')

    <script>

        function chooseFile(type) {
            console.log('{{config('app.url')}}/medium/select?type=' + type);
            layer.open({
                type: 2,
                area: ['90%', '90%'],
                fixed: false, //不固定
                maxmin: true,
                content: '{{config('app.url')}}/medium/select?type=' + type
            })
        }

        function choosedFiles(files, type) {
            // console.log('choosed files', files)
            // console.log(type)
            if (!files || !files.length) {
                return;
            }

            var html = '';
            var fileids = [];

            if (type === 'image') {
                files.forEach(function (file) {
                    console.log(file);
                    html += '<div class="selected-file">\n' +
                        '  <img src="' + file.url + '">\n' +
                        '</div>';
                    fileids.push(file.id);
                });
                console.log(fileids)

                $('.selected-images').html(html);
                $("input[name=images]").val(fileids.join('|'));
            }

            if (type === 'audio') {
                files.forEach(function (file) {

                    html += ' <div class="audio-item">\n' +
                        '                                        <audio controls src="' + file.url + '">\n' +
                        '                                            您的浏览器不支持 audio 标签。\n' +
                        '                                        </audio>\n' +
                        '                                        <div class="audio-name">\n' +
                        '                                            ' + file.client_origin_name + '\n' +
                        '                                        </div>\n' +
                        '                                    </div>';
                    fileids.push(file.id);
                });

                $('.selected-audios').html(html);
                $("input[name=audios]").val(fileids.join('|'));
            }

            if (type === 'video') {
                files.forEach(function (file) {
                    html += ' <div class="video-item">\n' +
                        '                                        <a data-href="' + file.url + '" href="javascript:void(0)">\n' +
                        '                                            ' + file.client_origin_name + '\n' +
                        '                                        </a>\n' +
                        '                                    </div>';
                    fileids.push(file.id);
                });
                $('.selected-videos').html(html);
                $("input[name=videos]").val(fileids.join('|'));

            }
        }

        function addPoint(o) {

            $(o).before(' <div class="row point-item">\n' +
                '                                    <div class="col-md-2">\n' +
                '                                        <input name="points[x][]" type="text" class="form-control" placeholder="x">\n' +
                '                                    </div>\n' +
                '                                    <div class="col-md-2">\n' +
                '                                        <input name="points[y][]" type="text" class="form-control" placeholder="y">\n' +
                '                                    </div>\n' +
                '                                    <div class="col-md-2">\n' +
                '                                        <button type="button" onclick="deletePoint(this)" class="btn btn-rounded btn-sm btn-icon btn-default"><i class="fa fa-times"></i></button>\n' +
                '                                    </div>\n' +
                '                                </div>');
        }

        function deletePoint(o) {
            $(o).parents('.point-item').remove();
        }


        $(document).ready(function () {
            $('#descriptions').summernote({
                lang: 'zh-CN',
                height: 500,
                width: 375,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    // ['table', ['table']],
                    ['insert', ['picture']],
                    ['view', ['help']]
                ]
            });

            $(document).on('click', '.video-item a', function () {
                var url = $(this).data('href');
                if (!url) {
                    return;
                }

                layer.open({
                    type: 1,
                    title: false,
                    area: ['630px', '360px'],
                    shade: 0.8,
                    closeBtn: 0,
                    shadeClose: true,
                    skin: 'yourclass',
                    content: `<video style="width: 100%; height: 100%;" controls="" autoplay="" name="media"><source src="${url}" type="video/mp4"></video>`
                });
            })
        })
    </script>

@endsection
