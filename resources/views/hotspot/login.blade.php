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

    <link href="{{ mix('/css/hotspot.css') }}" rel="stylesheet"/>
    <script src="{{ mix('/js/hotspot.js') }}" defer></script>

</head>
<body class="antialiased">
<div class="container">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (request()->input('error'))
        <div class="alert alert-danger">
            {{request()->input('error')}}
        </div>
    @endif
    <main class="form-signin w-50 m-auto">
        <form action="{{route('hotspot.check',$hotspot->id)}}" method="post">
            <input type="hidden" name="link-login" value="{{request()->input('link-login')}}">
            <input type="hidden" name="link-login-only" value="{{request()->input('link-login-only')}}">
            <input type="hidden" name="link-orig" value="{{request()->input('link-orig')}}">
            <input type="hidden" name="link-orig-esc" value="{{request()->input('link-orig-esc')}}">
            <input type="hidden" name="username" value="{{request()->input('username')}}">
            <input type="hidden" name="error" value="{{request()->input('error')}}">
            @csrf
            <h1 class="h3 mb-3 fw-normal">{{$hotspot->name}} <small>- {{$hotspot->description}}</small></h1>

            <div class="form-floating mb-3">
                <input type="text" name="first_name" value="batuhan" class="form-control">
                <label>Ad</label>
                @error('first_name')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-floating mb-3">
                <input type="text" name="last_name" value="ok" class="form-control">
                <label>Soyad</label>
                @error('last_name')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-floating mb-3">
                <input type="text" name="birth_year" value="1996" class="form-control">
                <label>Doğum Yılı</label>
                @error('birth_year')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-floating mb-3">
                <input type="text" name="identity_number" value="54637335882" class="form-control">
                <label>TC Kimlik</label>
                @error('identity_number')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button class="w-100 btn btn-lg btn-primary" type="submit">Giriş yap</button>
        </form>
    </main>
</div>
</body>
</html>
