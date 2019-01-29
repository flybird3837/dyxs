<!DOCTYPE html>
<html lang="zh_CN" class="app @yield('html-bg-color-class', '')">
<head>
    <meta charset="utf-8"/>
    <title>党员宣誓 | @yield('title')</title>
    <meta name="description" content="app, web app, responsive, admin dashboard, admin, flat, flat ui, ui kit, off screen nav"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app-url" content="{{config('app.url')}}">
    <link rel="stylesheet" href="{{ asset('css/note.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('css/plugins.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" type="text/css">
    <!--[if lt IE 9]>
    <script src="{{ asset('js/ie/html5shiv.js') }}" cache="false"></script>
    <script src="{{ asset('js/ie/respond.min.js')}}" cache="false"></script>
    <script src="{{ asset('js/ie/excanvas.js') }}" cache="false"></script>

    <![endif]-->
    <style>
        .pagination{
            margin: 0!important;
        }
    </style>
    @stack('links')
    @yield('styles')
</head>
<body>

@yield('main')


<script src="{{ asset('js/note.js') }}"></script>
<script src="{{ asset('js/plugins.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>

@yield('scripts')
<!-- Bootstrap --> <!-- App -->
</body>
</html>
