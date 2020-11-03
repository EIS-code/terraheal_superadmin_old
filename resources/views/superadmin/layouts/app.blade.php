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

    @stack('scripts')

    @once
        <script type="text/javascript">
            var routeProvince = '{{ route("getProvince") }}';
            var routeCity = '{{ route("getCity") }}';
        </script>
    @endonce

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <!-- <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"> -->
    <link href="{{ asset('css/fontawesome.css') }}" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/animate.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" type="text/css">
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
                @auth
                    <div class="d-flex main-flex">
                        <div class="aside sidebar wow fadeInLeft" data-wow-duration="2s" data-wow-delay="0.1s">
                            <div class="left-panel">
                                <div class="leave wow fadeInDown" data-wow-duration="3s" data-wow-delay="0.1s"><img src="{{ asset('images/leave.png') }}" alt="leave"/></div>
                                <div class="panel-logo"> <a href="javascript:void(0);"><img src="{{ asset('images/logo.png') }}" alt="logo"/></a> </div>
                                <div class="navigation">
                                    <ul class="main-menu">
                                        <li><a class="{{ (request()->is('superadmin.dashboard/*') ? 'active' : '') }}" href="{{ route('superadmin.dashboard') }}"><span class="menu-icon"><img src="{{ asset('images/dashbord.png') }}" alt="dashboard"/></span>{{ __('Dashboard') }}</a></li>
                                        <li><a class="{{ (request()->is('superadmin/centers/*') ? 'active' : '') }}" href="{{ route('centers.index') }}"><span class="menu-icon"><img src="{{ asset('images/shop.png') }}" alt="clients"/></span>{{ __('Centers') }}</a></li>
                                        <li><a class="{{ (request()->is('superadmin/clients/*') ? 'active' : '') }}" href="{{ route('clients.index') }}"><span class="menu-icon"><img src="{{ asset('images/clients.png') }}" alt="clients"/></span>{{ __('Clients') }}</a></li>
                                        <li><a class="{{ (request()->is('superadmin/therapists/*') ? 'active' : '') }}" href="{{ route('therapists.index') }}"><span class="menu-icon"><img src="{{ asset('images/therapist.png') }}" alt="therapist"/></span>{{ __('Therapists') }}</a></li>
                                        <li><a class="{{ (request()->is('superadmin/bookings/*') ? 'active' : '') }}" href="{{ route('bookings.index') }}"><span class="menu-icon"><img src="{{ asset('images/booking.png') }}" alt="booking"/></span>{{ __('Bookings') }}</a></li>
                                        <li><a class="{{ (request()->is('superadmin/services/*') ? 'active' : '') }}" href="{{ route('services.index') }}"><span class="menu-icon"><img src="{{ asset('images/services.png') }}" alt="services"/></span>{{ __('Services') }}</a></li>
                                        <li><a class="{{ (request()->is('superadmin/messages/*') ? 'active' : '') }}" href="{{ route('messages.index') }}"><span class="menu-icon"><img src="{{ asset('images/message.png') }}" alt="message"/></span>{{ __('Messages') }}</a></li>
                                        <li><a class="{{ (request()->is('superadmin/settings/*') ? 'active' : '') }}" href="{{ route('settings.index') }}"><span class="menu-icon"><img src="{{ asset('images/setting.png') }}" alt="setting"/></span>{{ __('Settings') }}</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                @endauth

                @yield('content')

                @auth
                        <div class="right-sidebar wow fadeInRight" data-wow-duration="2s" data-wow-delay="0.1s">
                            <div class="right-panel">
                                <div class="panel-top d-flex justify-content-between">
                                    <div class="announce" data-toggle="modal" data-target="#announcemodel"><img src="{{ asset('images/announce.png') }}" alt="announce"/><span class="counts">12</span></div>
                                    <div class="profile"><img src="{{ asset('images/profile.png') }}" alt="profile"/></div>
                                    <div class="notification" data-toggle="modal" data-target="#notifymodel"><img src="{{ asset('images/notification.png') }}" alt="notification"/><span class="counts">12</span></div>
                                </div>
                                @yield('right_content')

                                @sectionMissing('right_content')
                                    <div class="right-counts d-flex">
                                        <div class="info d-flex">
                                            <span class="info-img"><img src="{{ asset('images/app.png') }}" alt="app"/></span>
                                            <div class="info-right"> <span class="info-title">{{ __('App Users') }}</span> <span class="info-count">{{ $totalUsers }}</span> </div>
                                        </div>
                                        <div class="info d-flex">
                                            <span class="info-img"><img src="{{ asset('images/booking-b.png') }}" alt="booking"/></span>
                                            <div class="info-right"> <span class="info-title">{{ __('Home Booking') }}</span> <span class="info-count">{{ $totalBookings }}</span> </div>
                                        </div>
                                        <div class="info d-flex">
                                            <span class="info-img"><img src="{{ asset('images/sales.png') }}" alt="sales"/></span>
                                            <div class="info-right"> <span class="info-title">{{ __('Total Sales') }}</span> <span class="info-count">{{ $totalSales }}</span> </div>
                                        </div>
                                        <div class="info d-flex">
                                            <span class="info-img"><img src="{{ asset('images/percent.png') }}" alt="percent"/></span>
                                            <div class="info-right"> <span class="info-title">{{ __('Earnings') }}(%)</span> <span class="info-count">85</span> </div>
                                        </div>
                                    </div>
                                    <div class="item-list-main">
                                        <div class="item-top d-flex justify-content-between">
                                            <label>Top Items</label>
                                            <select>
                                                <option>Massages</option>
                                                <option>Massages 1</option>
                                                <option>Massages 2</option>
                                            </select>
                                        </div>
                                        <div class="list-items">
                                            <ul>
                                                @if(!empty($topItems) && !$topItems->isEmpty())
                                                    @foreach($topItems as $topItem)
                                                        <li>
                                                            <div class="item-img"><img src="{{ $topItem->icon }}" alt="icon1"/></div>
                                                            <div class="item-texts">
                                                                <strong>{{ $topItem->name }}</strong>
                                                                <p>{{ $topItem->description }}</p>
                                                            </div>
                                                            <div class="item-price"> $122.00 </div>
                                                        </li>
                                                    @endforeach
                                                @else
                                                    <li>
                                                        <div class="item-texts">
                                                            <strong>{{ __('No Items') }}</strong>
                                                        </div>
                                                    </li>
                                                @endif
                                            </ul>
                                            @if(!empty($topItems) && !$topItems->isEmpty())
                                                <div class="text-center viewall"><a href="javascript:void(0);">View All</a></div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="modal fade" id="notifymodel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLongTitle">Notifications</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="model-inner">
                                            <div class="notify-list">
                                                <ul>
                                                    <li>
                                                        <figure> <img src="{{ asset('images/placeholder.png') }}" alt="notify"/> </figure>
                                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took</p>
                                                        <span class="note-date">Today:3.15PM</span> 
                                                    </li>
                                                    <li>
                                                        <figure> <img src="{{ asset('images/placeholder.png') }}" alt="notify"/> </figure>
                                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took </p>
                                                        <span class="note-date">Today:3.15PM</span> 
                                                    </li>
                                                    <li>
                                                        <figure> <img src="{{ asset('images/placeholder.png') }}" alt="notify"/> </figure>
                                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took</p>
                                                        <span class="note-date">Today:3.15PM</span> 
                                                    </li>
                                                    <li>
                                                        <figure> <img src="{{ asset('images/placeholder.png') }}" alt="notify"/> </figure>
                                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took</p>
                                                        <span class="note-date">Today:3.15PM</span> 
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="announcemodel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLongTitle">Announcement</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="model-inner">
                                            <div class="model-field">
                                                <label>Topic:</label>
                                                <div class="model-para"> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s</div>
                                            </div>
                                            <div class="model-field">
                                                <label>Notes:</label>
                                                <div class="model-para"> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</div>
                                            </div>
                                            <div class="model-field">
                                                <label>Send To:</label>
                                                <div class="model-para none">
                                                    <select>
                                                        <option>All Therapists</option>
                                                        <option>All Therapists 1</option>
                                                        <option>All Therapists 2</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary">Send</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endauth
            </section>
        </main>
    </div>
</body>
</html>
