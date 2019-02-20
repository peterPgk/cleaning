<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/css/app.css">
    <title>{{ $company['name'] }}</title>

</head>
<body>
<div class="container" id="general_app" >

    <h3>{{ $company->name }}</h3>

    <p>{{ $company->email  }}</p>
    <p>{{ $company->data->website }}</p>
    <p>{{ $company->data->phone }}</p>
    <p>{{ $company->data->phone_2 }}</p>
    <p>{{ $company->data->address }}</p>
    <p>{{ $company->data->address_2 }}</p>

    <h4>Services</h4>

    <ul>

    @foreach( $company->services as $service )

        <li>
            <span>{{ $service->name }}</span> -
            <span>£{{ number_format($service->pivot->price, 2) }}</span>
        </li>

    @endforeach;
    </ul>
</div>

</body>
</html>
