@extends('layouts.main')

@section('title', '设备管理')

@section('content-header', '设备管理')

@section('content')
    <div class="row">

        <div class="col-lg-12">
            <!-- .breadcrumb -->
            <ul class="breadcrumb">
                <li><a href="{{route('dashboard.index')}}"><i class="fa fa-home"></i> 控制台</a></li>
                <li class="active"><i class="fa fa-cogs icon"></i> 设备管理</li>
            </ul>
            <!-- / .breadcrumb -->
        </div>
        <div class="col-lg-12">
            @include('shared.alert')
            <section class="panel panel-default">
                <header class="panel-heading"> 设备列表</header>
                <div class="row text-sm wrapper">
                    <div class="col-sm-5 m-b-xs">
                        @if(auth()->user()->hasRole('project_manage'))
                        <a href="{{ route('devices.create') }}" class="btn btn-default btn-sm">添加设备</a>
                        @endif
                    </div>
                    <div class="col-sm-7">
                        <form action="{{route('devices.index')}}" method="GET">
                            <div class="row">
                                <div class="col-md-3 col-md-offset-5">
                                    <input name="uuid" type="text" class="form-control input-sm" placeholder="设备uuid" value="{{$pageMap['uuid'] ?? ''}}">
                                </div>
                                <div class="col-md-3">
                                    <input name="name" type="text" class="form-control input-sm" placeholder="设备名称" value="{{$pageMap['name'] ?? ''}}">
                                </div>
                                <div class="col-md-1">
                                    <button class="btn btn-info btn-sm pull-right">搜索</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped b-t b-light text-sm">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>名称</th>
                            <th>UUID</th>
                            <th>位置</th>
                            <th>安装展点</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($devices as $device)
                            <tr>
                                <td>{{$device->id}}</td>
                                <td>{{$device->name}}</td>
                                <td>{{$device->uuid}}</td>
                                <td>x:{{$device->x ?? 0}}; y:{{$device->y ?? 0}}; z:{{$device->z ?? 0}}</td>
                                <td>{{$device->site ? $device->site->name : '未安装'}}</td>
                                <td>
                                    @if(auth()->user()->hasRole('project_manage'))
                                    <a href="{{ route('devices.edit', ['id' => $device->id]) }}">编辑</a>
                                    <a class="deleteConfirm" data-target="deleteForm-{{$device->id}}" href="javascript: void(0)">删除</a>
                                    {{--<a href="{{ route('devices.show', ['id' => $device->id]) }}">查看</a>--}}
                                    <form style="display: none;" id="deleteForm-{{$device->id}}" method="POST" action="{{route('devices.destroy', ['device' => $device->id])}}">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    @else
                                    ---
                                    @endif
                                </td>
                            </tr>


                        @endforeach
                        </tbody>
                    </table>
                </div>
                <footer class="panel-footer">
                    <div class="row">
                        <div class="col-sm-4 col-sm-offset-4 text-center">
                            <small class="text-muted inline m-t-sm m-b-sm">当前显示 20-30 | 总数 100</small>
                        </div>
                        <div class="col-sm-4 text-right text-center-xs">
                            {{$devices->links()}}
                        </div>
                    </div>
                </footer>
            </section>
        </div>

    </div>
@endsection