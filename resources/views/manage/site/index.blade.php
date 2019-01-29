@extends('layouts.main')

@section('title', '展点列表')

@section('content-header', '展点管理')

@section('content')
<link href="{{ asset('css/bootstrap-switch.min.css') }}" rel="stylesheet">
    <div class="row">

        <div class="col-lg-12">
            <!-- .breadcrumb -->
            <ul class="breadcrumb">
                <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-home"></i> 控制台</a></li>
                <li><i class="fa fa-list-ul"></i> 展点管理</li>
            </ul>
            <!-- / .breadcrumb -->
        </div>
        <div class="col-lg-12">
            @include('shared.alert')
            <section class="panel panel-default">
                <header class="panel-heading"> 展点列表</header>
                <div class="row text-sm wrapper">
                    <form method="GET" action="{{ route('sites.index') }}">
                        <div class="col-sm-5 m-b-xs">
                            {{--<select class="input-sm form-control input-s-sm inline">--}}
                            {{--<option value="0">Bulk action</option>--}}
                            {{--<option value="1">Delete selected</option>--}}
                            {{--<option value="2">Bulk edit</option>--}}
                            {{--<option value="3">Export</option>--}}
                            {{--</select>--}}
                            {{--<button class="btn btn-sm btn-default">Apply</button>--}}
                            <a href="{{ route('sites.create') }}" class="btn btn-default btn-sm">添加展点</a>
                        </div>
                        <div class="col-sm-4 m-b-xs">
                            {{--<div class="btn-group" data-toggle="buttons">--}}
                            {{--<label class="btn btn-sm btn-default active">--}}
                            {{--<input type="radio" name="options" id="option1">--}}
                            {{--Day </label>--}}
                            {{--<label class="btn btn-sm btn-default">--}}
                            {{--<input type="radio" name="options" id="option2">--}}
                            {{--Week </label>--}}
                            {{--<label class="btn btn-sm btn-default">--}}
                            {{--<input type="radio" name="options" id="option2">--}}
                            {{--Month </label>--}}
                            {{--</div>--}}
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" class="input-sm form-control" name="searchText" placeholder="输入查询信息">
                                <span class="input-group-btn">
                                <button class="btn btn-sm btn-default" type="submit">查询</button>
                            </span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped b-t b-light text-sm">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>展点名称</th>
                            <th>所属地图</th>
                            <th>VIP展点</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($sites as $site)
                            <tr>
                                <td>{{$site->id}}</td>
                                <td>{{$site->name}}</td>
                                <td>{{$site->map->name}}</td>
                                <td>
                                    <input type="checkbox" name="switch{{$site->id}}">
                                </td>
                                <td>
                                    <a href="{{ route('sites.edit', ['site' => $site->id]) }}">编辑</a>
                                    <a class="deleteConfirm" data-target="deleteForm-{{$site->id}}"
                                       href="javascript: void(0)">删除</a>
                                    <form style="display: none;" id="deleteForm-{{$site->id}}" method="POST"
                                          action="{{route('sites.destroy', ['site' => $site->id])}}">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    @if($site->qrcode)
                                        <a href="{{ route('site.qrcode.create', ['site' => $site->id]) }}">修改二维码</a>
                                        <a href="{{$site->qrcode}}">下载二维码</a>
                                    @else
                                        <a href="{{ route('site.qrcode.create', ['site' => $site->id]) }}">创建二维码</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <footer class="panel-footer">
                    <div class="row">
                        {{--<div class="col-sm-4 hidden-xs">--}}
                        {{--<select class="input-sm form-control input-s-sm inline">--}}

                        {{--<option value="1">批量删除</option>--}}

                        {{--</select>--}}
                        {{--<button class="btn btn-sm btn-default">提交</button>--}}
                        {{--</div>--}}
                        <div class="col-sm-4 text-center col-sm-offset-4">
                            <small class="text-muted inline m-t-sm m-b-sm">
                                当前显示 {{($sites->currentPage() - 1) * $sites->perPage()}}
                                -{{($sites->currentPage()) * $sites->perPage()}} | 总数 {{$sites->total()}} </small>
                        </div>
                        <div class="col-sm-4 text-right text-center-xs">
                            {{$sites->links()}}
                        </div>
                    </div>
                </footer>
            </section>
        </div>

    </div>
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="{{ asset('js/bootstrap-switch.min.js') }}"></script>
<script type="text/javascript">

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var isLoad = false;
        @foreach($sites as $site)
        $("[name='switch{{$site->id}}']").bootstrapSwitch({
            onSwitchChange: function (event, state) {
                if (isLoad){
                    $.ajax({
                        url: "/sites/set_vip",
                        method: "POST",
                        data: {siteid:{{$site->id}},isvip: state},
                        dataType: "json",
                        success: function success(data) {
                            if (data.error != 0) {
                            }
                        }
                    });
                }
            }     
        });
        $("[name='switch{{$site->id}}']").bootstrapSwitch('size', 'small');
        $("[name='switch{{$site->id}}']").bootstrapSwitch('onColor', 'success');
        $("[name='switch{{$site->id}}']").bootstrapSwitch('onText', '是');
        $("[name='switch{{$site->id}}']").bootstrapSwitch('offText', '否');
        $("[name='switch{{$site->id}}']").bootstrapSwitch('state', {{$site->isvip}});
        @endforeach
        isLoad = true;
</script>
@endsection