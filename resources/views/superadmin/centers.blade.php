@extends('superadmin.layouts.app')

@section('content')

<div class="content-sec wow fadeInUp" data-wow-duration="2s" data-wow-delay="0.1s" id="list">
    @include('superadmin.ajax.list-centers')
</div>

@section('right_content')
    <form action="{{ route('centers.index') }}" method="GET" id="search-form">
        <div class="search-top center-search">
            <input type="text" placeholder="{{ __('Search') }}" value="{{ $request->get('s', '') }}" name="s">
            <button type="button"><img src="{{ asset('images/search.png') }}" alt="search"></button>
        </div>
        <div class="center-form">
            <div class="form-field">
                <label>Sales</label>
                <select class="select-bg">
                    <option>Select...</option>
                    <option>Select 1</option>
                    <option>Select 2</option>
                </select>
            </div>
            <div class="form-field">
                <label>{{ __('Number of Services') }}</label>

                @if(!empty($massages) && !$massages->isEmpty())
                    <select class="select-bg" name="c">
                        <option value="">{{ __('Select...') }}</option>
                        @foreach ($massages as $massage)
                            <option value="{{ $massage->id }}" {{ $request->get('c') == $massage->id ? 'selected="true"' : '' }}>{{ $massage->name }}</option>
                        @endforeach
                    </select>
                @endif
            </div>
            <div class="form-field">
                <label>Payment</label>
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
                <label>TimeTable</label>
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
