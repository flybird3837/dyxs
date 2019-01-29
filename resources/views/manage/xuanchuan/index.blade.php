@extends('layouts.main')

@section('title', '宣传片管理')

@section('content-header', '宣传片管理')

@section('content')
    <div class="row">

        <div class="col-lg-12">
            <!-- .breadcrumb -->
            <ul class="breadcrumb">
                <li><a href="{{route('dashboard.index')}}"><i class="fa fa-home"></i> 控制台</a></li>
                <li class="active"><i class="fa fa-cogs icon"></i> 宣传片管理</li>
            </ul>
            <!-- / .breadcrumb -->
        </div>
        <div class="col-lg-12">
            @include('shared.alert')
            <section class="panel panel-default">
                <header class="panel-heading"> 宣传片列表</header>
                <div class="row text-sm wrapper">
                    <div class="col-sm-5 m-b-xs">

                    </div>
                    <div class="col-sm-7">
                        <div class="row">
                            <div class="col-md-12">
                                <span class="pull-right">
                                    <button class="btn btn-info btn-sm fa fa-upload icon">上传</button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped b-t b-light text-sm">
                        <thead>
                        <tr>
                            <th>视频</th>
                            <th>主题</th>
                            <th>简介</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($xuanchuans as $xuanchuan)
                            <tr>
                                <td><img src="{{$xuanchuan->video}}"/></td>
                                <td>{{$xuanchuan->name}}</td>
                                <td>{{$xuanchuan->intro}}</td>
                                <td>
                                    @if(auth()->user()->hasRole('project_manage'))
                                    <a href="{{ route('xuanchuans.edit', ['id' => $xuanchuan->id]) }}">删除</a>
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
                            {{$xuanchuans->links()}}
                        </div>
                    </div>
                </footer>
            </section>
        </div>

    </div>
@endsection