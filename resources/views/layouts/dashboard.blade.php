<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<meta name="csrf-token" content="{{ csrf_token() }}">
<head>
    <title>@yield('title')</title>

    <link rel="stylesheet" href="{{  URL::asset('apis/bootstrap/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{  URL::asset('apis/bootstrap-toggle/css/bootstrap-toggle.css') }}" />
    <link rel="stylesheet" href="{{  URL::asset('apis/bootstrap3-dialog/css/bootstrap-dialog.css') }}"/>
    <link rel="stylesheet" href="{{  URL::asset('apis/bootstrap-datetimepicker/css/bootstrap-datetimepicker4.css') }}"/>
    <link rel="stylesheet" href="{{  URL::asset('apis/DataTables/datatables.css') }}"/>
    <link rel="stylesheet" href="{{  URL::asset('apis/DataTables/DataTables-1.10.16/css/dataTables.bootstrap.css') }}"/>
    <link rel="stylesheet" href="{{  URL::asset('css/dashboard/navbar.css') }}"/>

    <script data-main="/js/report/main" src="/apis/require.js"></script>
</head>
<body>

<div id="app">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img class="dashboard-logo" src="/media/images/comunidad-conciente-logo.png">
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    &nbsp;
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        {{--                            <li><a href="{{ route('login') }}">Login</a></li>--}}
                        <li><a href="{{ route('register') }}">Registro</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
</div>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>

@yield('content')

@yield('scripts')
</body>

</html>
