<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="{{ asset('css/media.select.css') }}" type="text/css"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

<div id="app">
    <medium type="{{$type}}" field="{{$field}}"></medium>
</div>
</body>
<script src="{{ asset('js/media.select.js') }}"></script>
</html>