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
        @yield('header')

        @yield('info')

        @yield('data')

        @yield('other')

        @stack('scripts')
    </body>
</html>
    </div>
</body>
</html>