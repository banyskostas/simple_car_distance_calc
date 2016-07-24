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
                <h2>@yield('title')</h2>
            </div>
            <div class="content">
                @yield('content')
            </div>
        </div>

        <!-- Scripts -->
        <script src="{{URL::asset('js/main.js')}}"></script>
    </body>
</html>
