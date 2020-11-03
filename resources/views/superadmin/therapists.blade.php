@extends('superadmin.layouts.app')

@section('content')

<div class="content-sec wow fadeInUp" data-wow-duration="2s" data-wow-delay="0.1s" id="list">
    <div class="center-top d-flex justify-content-between">
        <div class="title">Clients</div>
        <div class="top-right">
        </div>
    </div>
    <div class="therapists-list">
        @if (!empty($therapists) && !$therapists->isEmpty())
            @foreach ($therapists as $therapist)
                <div class="therapy-col" onclick="location.href='';">
                    <div class="therapy-img">
                        <img src="{{ $therapist->profile_photo }}" alt=""/>
                    </div>
                    <div class="ratings">
                        <img src="{{ asset('images/star.png') }}" alt="rating"/>
                    </div>
                    <div class="therapy-name">{{ $therapist->name }}</div>
                    <div class="country">-, -</div>
                    <div class="center-bottom"> <a href="#"><i class="fas fa-eye"></i></a> <a href="#"><i class="far fa-envelope"></i></a> </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

@section('right_content')
    <form action="{{ route('therapists.index') }}" method="GET" id="search-form">
        <div class="search-top center-search">
            <input type="text" placeholder="Search by Name, Email, Tel, DOB, NIF, QR code">
            <button type="submit"><img src="{{ asset('images/search.png') }}" alt="search"></button>
        </div>
        <div class="center-form">
            <div class="form-field">
                <label>Alphabetical</label>
                <select class="select-bg">
                    <option>Select...</option>
                    <option>Select 1</option>
                    <option>Select 2</option>
                </select>
            </div>
            <div class="form-field">
                <label>First Register</label>
                <select class="select-bg">
                    <option>Select...</option>
                    <option>Select 1</option>
                    <option>Select 2</option>
                </select>
            </div>
            <div class="form-field">
                <label>Residence</label>
                <select class="select-bg">
                    <option>Select...</option>
                    <option>Select 1</option>
                    <option>Select 2</option>
                </select>
            </div>
            <div class="form-field">
                <label>Ratings</label>
                <select class="select-bg">
                    <option>Select...</option>
                    <option>Select 1</option>
                    <option>Select 2</option>
                </select>
            </div>
            <div class="form-field">
                <label>Type of service</label>
                <select class="select-bg">
                    <option>Select...</option>
                    <option>Select 1</option>
                    <option>Select 2</option>
                </select>
            </div>
            <div class="form-field">
                <label>Date of Birth</label>
                <select class="select-bg">
                    <option>Select...</option>
                    <option>Select 1</option>
                    <option>Select 2</option>
                </select>
            </div>
            <div class="form-field">
                <label>Gender</label>
                <select class="select-bg">
                    <option>Select...</option>
                    <option>Select 1</option>
                    <option>Select 2</option>
                </select>
            </div>
            <div class="form-field">
                <label>Home/Hotel Service</label>
                <select class="select-bg">
                    <option>Select...</option>
                    <option>Select 1</option>
                    <option>Select 2</option>
                </select>
            </div>
            <div class="form-field">
                <label>Type of Account</label>
                <select class="select-bg">
                    <option>Select...</option>
                    <option>Select 1</option>
                    <option>Select 2</option>
                </select>
            </div>
        </div>

        <button class="btn btn-primary font-white" onClick="window.location = window.location.href.split('?')[0];" type="button">{{ __('Clear') }}</button>
    </form>
@endsection

@endsection
