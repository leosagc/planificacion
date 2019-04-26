<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Styles -->
    <script src="{{asset('js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{asset('js/popper.min.js') }}"></script>
    <script src="{{asset('js/bootstrap.min.js') }}"></script>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css') }}"/>
    <link rel="stylesheet" href="{{asset('css/style.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet">
</head>
<body>
    @include('menu')
    <div class="main-container">
        <div class="row">
                @include('banner')
                @yield('content')
        </div>
    </div>
</body>
</html>