@extends('superadmin.layouts.app')

@section('content')

<div class="content-sec wow fadeInUp" data-wow-duration="2s" data-wow-delay="0.1s">
    <div class="center-top d-flex justify-content-between">
        <div class="title">Terra Heal Massage Center</div>
        <div class="top-right">
            <div class="add-center edit"> <a href="edit-center.php">Edit Details</a> </div>
        </div>
    </div>
    <div class="welcome-section massage-details">
        <figure><img src="{{ asset('images/centertop-bg.png') }}" alt=""/></figure>
        <div class="welcome-cont">
            <h2>Best place for<br>
                you harbal treament.
            </h2>
            <p>Welcome to Terra Heal Massage Center, where you can relax & enjoy life</p>
            <a href="javascript:void(0);" class="more-service">See All Services</a> 
        </div>
    </div>
    <div class="gr-content d-flex justify-content-between massage-detail-center">
        <div class="col first">
            <div class="col-inner">
                <div class="col-box"> <span class="count">{{ $data->massages }}</span> <span class="img-fig"><img src="{{ asset('images/services.png') }}" alt="messages"/></span> <span class="ttl">Massages</span> </div>
            </div>
            <div class="col-inner">
                <div class="col-box"> <span class="count">10</span> <span class="img-fig"><img src="{{ asset('images/therapies.png') }}" alt="therapies"/></span> <span class="ttl">Therapies</span> </div>
            </div>
            <div class="col-inner">
                <div class="col-box"> <span class="count">25</span> <span class="img-fig"><img src="{{ asset('images/therapist.png') }}" alt=""/></span> <span class="ttl">Staff</span> </div>
            </div>
        </div>
        <div class="col second">
            <div class="col-inner">
                <div class="col-box">
                    <div class="d-flex justify-content-between title-center">
                        <h3>Reviews</h3>
                        <select>
                            <option>Last Week</option>
                            <option>Last Month</option>
                        </select>
                    </div>
                    <div class="chart-sec">
                        <canvas id="chDonut3" width="100%"></canvas>
                    </div>
                    <p>
                        <label>Last Week</label>
                        <span>72.1%</span>
                    </p>
                </div>
            </div>
        </div>
        <div class="col third">
            <div class="col-inner">
                <div class="col-box"> <span class="count">50+</span> <span class="img-fig"><img src="{{ asset('images/shop.png') }}" alt=""/></span> <span class="ttl">Center Visits</span> </div>
            </div>
            <div class="col-inner">
                <div class="col-box"> <span class="count">500+</span> <span class="img-fig"><img src="{{ asset('images/feather-home.png') }}" alt="clients"/></span> <span class="ttl">Home Visits</span> </div>
            </div>
        </div>
    </div>
    <div class="bottom-content-sec massage-detail-bottom">
        <div class="row">
            <div class="col-md-7">
                <div class="chart-inner">
                    <div class="in-top d-flex justify-content-between">
                        <h3>Earnings</h3>
                        <select>
                            <option>Last Week</option>
                            <option>Last Month</option>
                            <option>Last Year</option>
                        </select>
                    </div>
                    <canvas id="chBar" width="100%"></canvas>
                </div>
            </div>
            <div class="col-md-5">
                <div class="chart-inner">
                    <div class="in-top d-flex">
                        <h3>Summary</h3>
                        <select>
                            <option>Last Week</option>
                            <option>Last Month</option>
                            <option>Last Year</option>
                        </select>
                    </div>
                    <div class="summary-list">
                        <ul>
                            <li>
                                <div class="left-sum">
                                    <label>Total Bookings</label>
                                    <div class="sum-count">258</div>
                                </div>
                                <div class="sum-right"> <span class="pos up"></span> </div>
                            </li>
                            <li>
                                <div class="left-sum">
                                    <label>Canceled Bookings</label>
                                    <div class="sum-count">15</div>
                                </div>
                                <div class="sum-right"> <span class="pos up"></span> </div>
                            </li>
                            <li>
                                <div class="left-sum">
                                    <label>New Clients</label>
                                    <div class="sum-count">51</div>
                                </div>
                                <div class="sum-right"> <span class="pos down"></span> </div>
                            </li>
                            <li>
                                <div class="left-sum">
                                    <label>Vouchers Sold</label>
                                    <div class="sum-count">40</div>
                                </div>
                                <div class="sum-right"> <span class="pos up"></span> </div>
                            </li>
                            <li>
                                <div class="left-sum">
                                    <label>Packs Sold</label>
                                    <div class="sum-count">25</div>
                                </div>
                                <div class="sum-right"> <span class="pos down"></span> </div>
                            </li>
                            <li>
                                <div class="left-sum">
                                    <label>Total Earnings</label>
                                    <div class="sum-count">$5000</div>
                                </div>
                                <div class="sum-right"> <span class="pos up"></span> </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
