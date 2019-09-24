<!DOCTYPE html>
<html lang="en">

<head>

    @section('meta')
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
    @show

    @section('title')
        <title>Spectre Tech Test</title>
    @show

    @section('css')
        <!-- Bootstrap core CSS -->
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
        <link href="{{ asset('/css/app.css') }}">
    @show

</head>

<body>

    @include('navigation')

    <div class="container">
        @yield('content')
    </div>

    @section('js')
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.4.1.min.js"
                integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
                crossorigin="anonymous"></script>
        <!-- Bootstrap core JavaScript -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    @show

</body>

</html>
