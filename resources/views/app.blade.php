<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
{{--        <meta name="viewport" content="width=device-width, initial-scale=1.0">--}}
{{--        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>--}}
        <meta http-equiv="X-UA-Compatible" content="ie=edge"/>

        <meta name="msapplication-TileColor" content="#206bc4"/>
        <meta name="theme-color" content="#206bc4"/>
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
        <meta name="apple-mobile-web-app-capable" content="yes"/>
        <meta name="mobile-web-app-capable" content="yes"/>
        <meta name="HandheldFriendly" content="True"/>
        <meta name="MobileOptimized" content="320"/>
        <link rel="icon" href="{{asset('favicon.ico')}}" type="image/x-icon"/>
        <link rel="shortcut icon" href="{{asset('favicon.ico')}}" type="image/x-icon"/>

        <link href="{{ mix('/css/app.css') }}" rel="stylesheet" />
        <script src="{{ mix('/js/app.js') }}" defer></script>
        @routes
    </head>
    <body class="antialiased">
        @inertia
    </body>

</html>
