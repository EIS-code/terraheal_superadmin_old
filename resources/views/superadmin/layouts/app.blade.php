<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script type="text/javascript" src="{{ asset('js/app.js') }}" defer></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap.bundle.min.js') }}" defer></script> 
    <script type="text/javascript" src="{{ asset('js/wow.js') }}" defer></script> 
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.js" defer></script> 
    <script type="text/javascript" src="{{ asset('js/custom.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <!-- <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"> -->
    <link href="{{ asset('css/fontawesome.css') }}" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/animate.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" type="text/css">
</head>
<body>
    <div id="app">
        <!-- <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar --><!-- 
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar --><!-- 
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links --><!-- 
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav> -->

        <main class="py-0">
            <section class="home-section main-content">
                <div class="d-flex main-flex">
                    <div class="aside sidebar wow fadeInLeft" data-wow-duration="2s" data-wow-delay="0.1s">
                        <div class="left-panel">
                            <div class="leave wow fadeInDown" data-wow-duration="3s" data-wow-delay="0.1s"><img src="images/leave.png" alt="leave"/></div>
                            <div class="panel-logo"> <a href="javascript:void(0);"><img src="images/logo.png" alt="logo"/></a> </div>
                            <div class="navigation">
                                <ul class="main-menu">
                                    <li><a class="active" href="home.php"><span class="menu-icon"><img src="{{ asset('images/dashbord.png') }}" alt="dashboard"/></span>{{ __('Dashboard') }}</a></li>
                                    <li><a href="centers.php"><span class="menu-icon"><img src="{{ asset('images/shop.png') }}" alt="clients"/></span>{{ __('Centers') }}</a></li>
                                    <li><a href="clients.php"><span class="menu-icon"><img src="{{ asset('images/clients.png') }}" alt="clients"/></span>{{ __('Clients') }}</a></li>
                                    <li><a href="therapists.php"><span class="menu-icon"><img src="{{ asset('images/therapist.png') }}" alt="therapist"/></span>{{ __('Therapists') }}</a></li>
                                    <li><a href="booking.php"><span class="menu-icon"><img src="{{ asset('images/booking.png') }}" alt="booking"/></span>{{ __('Bookings') }}</a></li>
                                    <li><a href="services.php"><span class="menu-icon"><img src="{{ asset('images/services.png') }}" alt="services"/></span>{{ __('Services') }}</a></li>
                                    <li><a href="massage.php"><span class="menu-icon"><img src="{{ asset('images/message.png') }}" alt="message"/></span>{{ __('Messages') }}</a></li>
                                    <li><a href="settings.php"><span class="menu-icon"><img src="{{ asset('images/setting.png') }}" alt="setting"/></span>{{ __('Settings') }}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    @yield('content')
                </div>
            </section>
        </main>
    </div>
</body>
</html>
