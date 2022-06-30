<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="pragma" content="no-cache" />
    <meta http-equiv="expires" content="-1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Internet hotspot - Log in</title>
</head>

<body>
<form name="redirect" action="{{request()->input('link-login-only')}}" method="post">
    <input type="text" name="username" value="{{$username}}">
    <input type="text" name="password" value="{{$password}}">
    <input type="hidden" name="dst" value="{{request()->input('link-orig')}}">
    <input type="submit" value="GIRIS YAP"/>
</form>
<script>
    // document.redirect.submit();
</script>
</body>

</html>
