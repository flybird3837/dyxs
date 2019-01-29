@extends('layouts.main')

@section('styles')
    <style>
        .main-container {
            position: absolute;
            bottom: 60px;
            top: 60px;
        }

        .canvas-container {
            display: flex;
            flex: 1;
        }
        .site-list .list-group-item a {
            margin-right: 5px;
        }
    </style>

@endsection

@section('content')

    <div class="row">

        <div class="col-lg-12">
            <!-- .breadcrumb -->
            <ul class="breadcrumb">
                <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-home"></i> 控制台</a></li>
                <li><a href="{{route('maps.index')}}"><i class="fa fa-cogs icon"></i> 地图管理</a></li>
                <li class="active">编辑展点</li>
            </ul>
            <!-- / .breadcrumb -->
        </div>

        <div class="col-lg-12 main-container">
            <div class="row" style="height: 100%;">
                <div class="col-md-9" style="height: 100%;">
                    <div class="canvas-container" data-id="{{$map->id}}" style="height: 100%;">

                    </div>
                </div>
                <div class="col-md-3">
                    <div class="list-group bg-white site-list">
                        @foreach($map->sites as $site)
                            <div class="list-group-item" data-id="{{$site->id}}">
                                <span>{{$site->name}}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{asset('js/cax.js')}}"></script>
@endpush

@section('scripts')
    <script>
        const mapUlr = '{{$mapUrl}}';
        new Path({container: ".canvas-container", map: mapUlr});
    </script>
@endsection

