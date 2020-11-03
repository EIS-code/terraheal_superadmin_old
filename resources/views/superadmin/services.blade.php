@extends('superadmin.layouts.app')

@section('content')

<div class="content-sec wow fadeInUp" data-wow-duration="2s" data-wow-delay="0.1s" id="list">
    <div class="center-top d-flex justify-content-between">
        <div class="title">Services</div>
        <div class="top-right">
            <div class="add-center"> <a href="add-massage.php">Add Services</a> </div>
        </div>
    </div>
    <div class="service-main">
        <div class="all-services">
            <ul>
                @if (!empty($massages) && !$massages->isEmpty())
                    @foreach ($massages as $massage)
                        <li class="checkbox-msg" onclick="location.href='';">
                            <input type="checkbox" name="">

                            <div class="msg-bx">
                                <div class="msg-icon"><img src="{{ $massage->icon }}" alt=""></div>
                                <p>{{ $massage->name }}</p>
                                <div class="ratings">
                                    <img src="{{ asset('images/star.png') }}" alt="">
                                </div>
                                <div class="service-icons d-flex justify-content-between">
                                    <i class="fas fa-eye"></i>
                                    <i class="fas fa-pencil-alt"></i>
                                </div>
                            </div>
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>
    </div>
</div>

@section('right_content')
    <form action="{{ route('services.index') }}" method="GET" id="search-form">
        <div class="search-top center-search">
            <input type="text" placeholder="Search by massage name or therapie name">
            <button type="submit"><img src="{{ asset('images/search.png') }}" alt="search"></button>
        </div>
        <div class="service-side">
            <div class="pf-bx-inner">
                <h4>Services Types</h4>
                <ul>
                    <li>
                        <input type="radio" name="radio" value="one">
                        <label>massage</label>
                    </li>
                    <li>
                        <input type="radio" name="radio" value="two">
                        <label>therapie</label>
                    </li>
                </ul>
                <h4>Delivery Types</h4>
                <ul>
                    <li>
                        <input type="radio" name="radio" value="three">
                        <label>Home / hotel</label>
                    </li>
                    <li>
                        <input type="radio" name="radio" value="four">
                        <label>Center</label>
                    </li>
                </ul>
            </div>
        </div>

        <button class="btn btn-primary font-white" onClick="window.location = window.location.href.split('?')[0];" type="button">{{ __('Clear') }}</button>
    </form>
@endsection

@endsection
