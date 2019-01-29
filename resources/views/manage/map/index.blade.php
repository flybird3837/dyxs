@extends('layouts.main')

@section('title', '地图列表')

@section('content-header', '地图管理')

@section('styles')

    <style>
        .map-image {
            width: 100%;
            height: 100%;
            overflow: hidden;
            max-height: 400px;
        }

        .map-image img {
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
                <li><i class="fa fa-indent icon"></i> 地图管理</li>
            </ul>
            <!-- / .breadcrumb -->
        </div>
        <div class="col-lg-12 m-b-sm text-right">
            <a href="{{ route('maps.create') }}" class="btn btn-s-md btn-danger"><i
                        class="fa fa-plus m-r-sm"></i>添加新地图</a>
        </div>
        <div class="col-lg-12">
            @include('shared.alert')
            {{--<div class="text-center m-t-lg">--}}
            {{--还没有地图哦！--}}
            {{--</div>--}}
            <div class="row">
                @foreach($maps as $map)
                    <div class="col-lg-6">
                        <section class="panel panel-default">
                            <div class="panel-heading text-center">
                                {{$map->name}}
                            </div>
                            <div class="map-image">
                                <img src="{{$map->image->url}}">
                            </div>
                            <ul class="list-group no-radius">
                                <li class="list-group-item"><span class="pull-right">{{$map->sites_count}}</span> 展点数
                                </li>
                                <li class="list-group-item"><span class="pull-right">{{$map->devices_count}}</span> 设备数
                                </li>
                                <li class="list-group-item text-right">
                                    <a href="{{route('maps.edit', ['map' => $map->id])}}">编辑</a>
                                    <a href="{{route('maps.sites.edit', ['map' => $map->id])}}">展点编辑</a>
                                    <a href="{{route('maps.sites.path', ['map' => $map->id])}}">路径编辑</a>
                                    {{--<a href="{{route('maps.show', ['map' => $map->id])}}">查看</a>--}}
                                </li>
                            </ul>
                        </section>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>


</script>
@endsection