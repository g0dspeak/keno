<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Keno</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    @yield('header_fonts')


    <!-- Styles -->
    <link href="/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="/css/app.custom.css" rel="stylesheet" type="text/css">
    @yield('header_styles')

    <!-- Scripts -->
    <script type="text/javascript" src="/js/jquery.min.js"></script>
    <script type="text/javascript" src="/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/js/app.custom.js"></script>
    @yield('header_scripts')
    <style>

    </style>
</head>
<body class="blurBg-false">
<div class="flex-center position-ref full-height">
    <div class="content">
        <div class="title m-b-md">
            <span>Keno simulation</span>
        </div>

        <div class="keno">
            @yield('content')
        </div>
    </div>
</div>
<footer>
    @yield('footer_section')
</footer>
</body>
</html>
