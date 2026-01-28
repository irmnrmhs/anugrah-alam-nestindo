<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Document')</title>

    <link rel="stylesheet" href="{{ public_path('css/style.css') }}">

    @stack('styles')
</head>
<body>
    <table class="header">
        @yield('header')
    </table>

    <table class="info">
        @yield('info')
    </table>

    <table class="data">
        @yield('data')
    </table>

    <table class="other">
        @yield('other')
    </table>

    @stack('scripts')
</body>
</html>