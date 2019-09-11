<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <!-- Styles -->
    <link href="{{ asset('css/app.css', config('app.enabled_ssl')) }}" rel="stylesheet">
</head>
<body>
    <div class="container" id="app">
        @yield('content')
    </div>
    @yield('scripts')
    <script src="{{ asset('js/app.js', config('app.enabled_ssl')) }}"></script>
</body>
</html>
