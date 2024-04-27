<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', '人生のSTEPを共有使用') }}</title>

        <!-- フォント -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- スタイル -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        @routes
        <!-- sctipt 多言語化対応するためにlocaleを定義 -->
        <script>
          window.App = {!! json_encode(['locale' => app()->getLocale()]) !!};
        </script>
        @inertiaHead
    </head>
    
    <body class="font-sans antialiased">
        @inertia
        <!-- sctipt -->
        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
