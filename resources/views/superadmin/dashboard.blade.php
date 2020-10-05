@extends('superadmin.layouts.app')

@section('content')


<div class="content-sec wow fadeInUp" data-wow-duration="2s" data-wow-delay="0.1s">
    <div class="center-top d-flex justify-content-between">
        <div class="title">{{ __('Dashboard') }}</div>
        <div class="top-right">
            <div class="search-top">
                <input type="text" placeholder="Search"/>
                <button type="submit"><img src="{{ asset('images/search.png') }}" alt="search"/></button>
            </div>
        </div>
    </div>
    <div class="welcome-section">
        <figure>
            <img src="{{ asset('images/welcome.png') }}" alt="welcome"/>
        </figure>
        <div class="welcome-cont">
            <h2>
                {{ __('Hello Superadmin, Greetings for the day.') }}
                <p>{{ __('Today is') }} <span class="day">{{ $now->format('l') }}</span> {{ __('and date is') }} <span class="date">{{ $now->format('d/m/Y') }}.</span></p>
            </h2>
        </div>
    </div>
    <div class="gr-content d-flex justify-content-between">
        <div class="col first">
            <div class="col-inner">
                <div class="col-box"> <span class="count">{{ $totalMassages }}</span> <span class="img-fig"><img src="{{ asset('images/services.png') }}" alt="messages"/></span> <span class="ttl">{{ __('Massages') }}</span></div>
            </div>
            <div class="col-inner">
                <div class="col-box"> <span class="count">40</span> <span class="img-fig"><img src="{{ asset('images/therapies.png') }}" alt="therapies"/></span> <span class="ttl">{{ __('Therapies') }}</span></div>
            </div>
            <div class="col-inner">
                <div class="col-box"> <span class="count">{{ $totalShops }}+</span> <span class="img-fig"><img src="{{ asset('images/shop.png') }}" alt="shop"/></span> <span class="ttl">{{ __('Centers') }}</span></div>
            </div>
        </div>
        <div class="col second">
            <div class="col-inner">
                <div class="col-box">
                    <div class="d-flex justify-content-between title-center">
                        <h3>{{ __('Client Satisfaction') }}</h3>
                    </div>
                    <div class="chart-sec">
                        <canvas id="chDonut3" width="100%"></canvas>
                    </div>
                    <p>
                        <label>{{ __('Last Week') }}</label>
                        <span>72.1%</span>
                    </p>
                </div>
            </div>
        </div>
        <div class="col third">
            <div class="col-inner">
                <div class="col-box"> <span class="count">{{ $totalTherapists }}+</span> <span class="img-fig"><img src="{{ asset('images/therapist.png') }}" alt="{{ __('Therapists') }}"/></span> <span class="ttl">{{ __('Therapists') }}</span></div>
            </div>
            <div class="col-inner">
                <div class="col-box"> <span class="count">{{ $totalUsers }}+</span> <span class="img-fig"><img src="{{ asset('images/clients.png') }}" alt="clients"/></span> <span class="ttl">{{ __('Clients') }}</span></div>
            </div>
        </div>
    </div>
    <div class="bottom-content-sec">
        <div class="row">
            <div class="col-md-5">
                <div class="chart-inner">
                    <div class="in-top d-flex">
                        <h3>{{ __('Activity Feed') }}</h3>
                        <span class="activity">6</span> 
                    </div>
                    <div class="feed-list">
                        <div class="feed">
                            <div class="feed-img"><img src="{{ asset('images/placeholder.png') }}" alt="feed"/></div>
                            <div class="feed-cont">
                                <div class="feed-top d-flex justify-content-between">
                                    <div class="feed-title">Janet Williams<span>FOUNDER</span></div>
                                    <div class="feed-date">14:21pm</div>
                                </div>
                                <p>Owner and founder, Janet Williams has a passion for holistic whole body balance and care.</p>
                            </div>
                        </div>
                        <div class="feed">
                            <div class="feed-img"><img src="{{ asset('images/placeholder2.png') }}" alt="placeholder2"/></div>
                            <div class="feed-cont">
                                <div class="feed-top d-flex justify-content-between">
                                    <div class="feed-title">Erica Forney<span>NAIL ARTIST</span></div>
                                    <div class="feed-date">18:11pm</div>
                                </div>
                                <p>A 2014 graduate of Tri Cities Beauty College, Vera specialize in designs and nail enhancements.</p>
                            </div>
                        </div>
                        <div class="feed">
                            <div class="feed-img"><img src="{{ asset('images/placeholder3.png') }}" alt="placeholder3"/></div>
                            <div class="feed-cont">
                                <div class="feed-top d-flex justify-content-between">
                                    <div class="feed-title">Erica Forney<span>NAIL ARTIST</span></div>
                                    <div class="feed-date">18:11pm</div>
                                </div>
                                <p>A 2014 graduate of Tri Cities Beauty College, Vera specialize in designs and nail enhancements.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="chart-inner">
                    <div class="in-top d-flex justify-content-between">
                        <h3>{{ __('Earnings') }}</h3>
                        <select>
                            <option>{{ __('Last Week') }}</option>
                            <option>{{ __('Last Month') }}</option>
                            <option>{{ __('Last Year') }}</option>
                        </select>
                    </div>
                    <canvas id="chBar" width="100%"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
