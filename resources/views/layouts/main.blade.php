@extends('layouts.app')

@section('main')
    <section class="hbox stretch"> <!-- .aside -->
        <aside class="bg-dark aside-md" id="nav">
            <section class="vbox">
                <header class="header dker navbar navbar-fixed-top-xs">
                    <div class="navbar-header">
                        <a class="btn btn-link visible-xs" data-toggle="class:nav-off-screen" data-target="#nav">
                            <i class="fa fa-bars"></i>
                        </a>
                        <a href="#" class="navbar-brand" data-toggle="fullscreen">
                            @if(auth()->user()->hasRole('administrator'))
                            <img src="{{ asset('images/logo.png') }}" class="m-r-sm"> <span class="hidden-nav-xs">后台管理系统</span>
                            @else
                            <img src="@if(auth()->user()->project->image){{ asset('storage/avatar') }}/{{auth()->user()->project->image}}@else{{ asset('images/logo.png') }}@endif" class="m-r-sm"> 
                            <span class="hidden-nav-xs" style="font-size:15px;font-weight:bold">{{ auth()->user()->project->name }}</span>
                            @endif
                        </a>
                        <a class="btn btn-link visible-xs" data-toggle="dropdown" data-target=".nav-user">
                            <i class="fa fa-cog"></i>
                        </a>
                    </div>
                </header>
                <section class="w-f scrollable">
                    <!-- nav -->
                    <div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0"
                         data-size="7px" data-railOpacity="0.2">
                        <div class="clearfix wrapper bg-primary nav-user hidden-xs">
                            <div class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <span class="thumb-sm avatar pull-left m-r-sm">
                                    <img src="{{ asset('images/avatar.jpg') }}">
                                </span>
                                    <span class="hidden-nav-xs clear"> <strong>{{ auth()->user()->name }}</strong>
                                    <b class="caret caret-white"></b>
                                    <span class="text-muted text-xs block">{{ auth()->user()->phone }}</span>
                                </span>
                                </a>
                                <ul class="dropdown-menu animated fadeInRight m-t-xs">
                                    <span class="arrow top hidden-nav-xs"></span>
                                    <li><a href="#">修改密码</a></li>
                                    {{--<li><a href="profile.html">Profile</a></li>--}}
                                    {{--<li><a href="#"> <span class="badge bg-danger pull-right">3</span> Notifications </a>--}}
                                    {{--</li>--}}
                                    {{--<li><a href="docs.html">Help</a></li>--}}
                                    <li class="divider"></li>
                                    <li><a href="#" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">退出</a></li>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                        @csrf
                                    </form>
                                </ul>
                            </div>
                        </div>
                        <nav class="nav-primary hidden-xs">
                            <ul class="nav">

                                <li class="{{Route::currentRouteName() === 'dashboard.index' ? 'active' : ''}}">
                                    <a href="{{route('dashboard.index')}}">
                                        <i class="fa fa-home icon">
                                            <b class="bg-danger"></b>
                                        </i>
                                        <span>控制台</span>
                                    </a>
                                </li>
                                @if(auth()->user()->hasRole('administrator'))
                                    <li class="{{Route::currentRouteName() === 'projects.index' ? 'active' : ''}}">
                                        <a href="{{route('projects.index')}}">
                                            <i class="fa fa-list-ul icon">
                                                <b class="bg-danger"></b>
                                            </i>
                                            <span>权限管理</span>
                                        </a>
                                    </li>
                                @endif
                                @if(auth()->user()->hasRole('project_manage') || auth()->user()->hasRole('project_maintenance'))
                                    <li class="{{Route::currentRouteName() === 'dangyuans.index' ? 'active' : ''}}">
                                        <a href="{{route('dangyuans.index')}}">
                                            <i class="fa fa-cogs icon">
                                                <b class="bg-warning"></b>
                                            </i>
                                            <span>党员信息管理</span>
                                        </a>
                                    </li>
                                @endif
                                @if(auth()->user()->hasRole('project_manage') || auth()->user()->hasRole('project_maintenance'))
                                    <li class="{{Route::currentRouteName() === 'xuanchuans.index' ? 'active' : ''}}">
                                        <a href="{{route('xuanchuans.index')}}">
                                            <i class="fa fa-map-marker icon">
                                                <b class="bg-success"></b>
                                            </i>
                                            <span>宣传片</span>
                                        </a>
                                    </li>
                                @endif
                                @if(auth()->user()->hasRole('project_manage') || auth()->user()->hasRole('project_maintenance'))
                                    <li class="{{Route::currentRouteName() === 'projects.edit' ? 'active' : ''}}">
                                        <a href="{{route('projects.edit', auth()->user()->project_id)}}">
                                            <i class="fa fa-indent icon">
                                                <b class="bg-info"></b>
                                            </i>
                                            <span>党组织管理</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                        <!-- nav -->
                    {{--<nav class="nav-primary hidden-xs">--}}
                    {{--<ul class="nav">--}}
                    {{--@foreach($menus as $menu)--}}
                    {{--@if($menu['childs'])--}}
                    {{--<li class="{{ $menu['active'] ? 'active' : '' }}">--}}
                    {{--<a href="{{ $menu['route_name'] ? route($menu['route_name']) : '#' }}"--}}
                    {{--class="{{ $menu['active'] ? 'active' : '' }}">--}}
                    {{--<i class="fa fa-columns icon"><b--}}
                    {{--class="{{ $menu['bgcolor_class'] }}"></b></i>--}}
                    {{--<span class="pull-right"> <i class="fa fa-angle-down text"></i>--}}
                    {{--<i class="fa fa-angle-up text-active"></i>--}}
                    {{--</span>--}}
                    {{--<span>{{ $menu['name'] }}</span>--}}
                    {{--</a>--}}
                    {{--<ul class="nav lt">--}}
                    {{--@foreach($menu['childs'] as $childMenu)--}}
                    {{--<li class="{{ $childMenu['active'] ? 'active' : '' }}">--}}
                    {{--<a href="{{ $childMenu['route_name'] ? route($childMenu['route_name']) : '#' }}">--}}
                    {{--<i class="fa fa-angle-right"></i>--}}
                    {{--<span>{{$childMenu['name']}}</span>--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    {{--@endforeach--}}
                    {{--</ul>--}}
                    {{--</li>--}}
                    {{--@else--}}
                    {{--<li class="{{ $menu['active'] ? 'active' : '' }}">--}}
                    {{--<a href="{{ $menu['route_name'] ? route($menu['route_name']) : '#' }}">--}}
                    {{--<i class="fa {{ $menu['icon'] }} icon">--}}
                    {{--<b class="{{ $menu['bgcolor_class'] }}"></b>--}}
                    {{--</i>--}}
                    {{--<span>{{ $menu['name'] }}</span>--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    {{--@endif--}}
                    {{--@endforeach--}}
                    {{--</ul>--}}
                    {{--</nav>--}}
                    <!-- / nav -->
                    </div>
                    <!-- / nav -->
                </section>

                <footer class="footer lt hidden-xs b-t b-dark">
                    <a href="#nav" data-toggle="class:nav-xs" class="pull-right btn btn-sm btn-dark btn-icon"> <i
                                class="fa fa-angle-left text"></i> <i class="fa fa-angle-right text-active"></i> </a>
                </footer>
            </section>
        </aside>
        <!-- /.aside -->
        <section id="content">
            <section class="vbox">
                <section>
                    <section class="hbox stretch">
                        <section>
                            <section class="vbox">
                                <header class="header bg-white b-b b-light">
                                    <p>@yield('content-header')</p>
                                </header>
                                <section class="scrollable wrapper">
                                    @yield('content')
                                </section>
                                <footer class="footer bg-white b-t b-light">
                                    {{--<p>This is a footer</p>--}}
                                </footer>
                            </section>
                        </section>
                        {{--<aside class="bg-light lter b-l aside-md"></aside>--}}
                    </section>
                </section>
            </section>
            <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a>
        </section>
    </section>
@endsection