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
<div class="right-sidebar wow fadeInRight" data-wow-duration="2s" data-wow-delay="0.1s">
    <div class="right-panel">
        <div class="panel-top d-flex justify-content-between">
            <div class="announce" data-toggle="modal" data-target="#announcemodel"><img src="{{ asset('images/announce.png') }}" alt="announce"/><span class="counts">12</span></div>
            <div class="profile"><img src="{{ asset('images/profile.png') }}" alt="profile"/></div>
            <div class="notification" data-toggle="modal" data-target="#notifymodel"><img src="{{ asset('images/notification.png') }}" alt="notification"/><span class="counts">12</span></div>
        </div>
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
                                <figure> <img src="images/placeholder.png" alt="notify"/> </figure>
                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took</p>
                                <span class="note-date">Today:3.15PM</span> 
                            </li>
                            <li>
                                <figure> <img src="images/placeholder.png" alt="notify"/> </figure>
                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took </p>
                                <span class="note-date">Today:3.15PM</span> 
                            </li>
                            <li>
                                <figure> <img src="images/placeholder.png" alt="notify"/> </figure>
                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took</p>
                                <span class="note-date">Today:3.15PM</span> 
                            </li>
                            <li>
                                <figure> <img src="images/placeholder.png" alt="notify"/> </figure>
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

@endsection
