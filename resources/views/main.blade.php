<!DOCTYPE html>
<html lang="en" ng-app="mainApp">
    <head>
    <meta charset="utf-8">
        <title>Softdator - @yield('title')</title>
        <link rel="stylesheet" type="text/css" href="{{URL::asset('assets\css\app.css')}}">
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h1>@yield('title')</h1>
            </div>
            <div class="content">
                <div class="inner-content">
                    @yield('content')
                </div>
            </div>
        </div>

        <!-- Scripts -->
        <script src="{{URL::asset('js/main.js')}}"></script>
    </body>
</html>
