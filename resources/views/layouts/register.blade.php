<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/css/app.css">

    @yield('css')

    <title>@yield('title')</title>

</head>
<body>
<div class="container-fluid register" id="general_app" v-cloak>
    @yield('content')
</div>

<script src="https://js.stripe.com/v2/"></script>
<script src="/js/register.js"></script>
@yield('js')
</body>
</html>