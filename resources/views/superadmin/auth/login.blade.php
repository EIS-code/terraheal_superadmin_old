@extends('superadmin.layouts.app')

@section('content')
<section class="login-section">
    <div class="login-inner">
        <!-- Login-left-section -->
        <div class="login-left wow fadeInLeft" data-wow-duration="2s" data-wow-delay="0.1s" >
            <div class="leaves wow fadeInUp" data-wow-duration="3s" data-wow-delay="0.1s"></div>
        </div>

        <!-- Login-right-section -->
        <div class="login-right wow fadeInRight" data-wow-duration="2s" data-wow-delay="0.1s" >
            <div class="login-right-inner">
                <div class="login-logo wow fadeInDown" data-wow-duration="3s" data-wow-delay="0.1s">
                    <a href="javascript:void(0);">
                        <img src="{{ asset('images/logo.png') }}" alt="logo"/>
                    </a>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="login-content">
                        <h4>{{ __('Hello!') }}</h4>

                        <h2>{{ __('Welcome Back') }}</h2>

                        <p>{{ __('You are just a step away from your dashboard.') }}</p>

                        <div class="input-field-grp">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="User Email">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="input-field-grp">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                            <label>{{ __('Remember Me') }}</label>
                        </div>

                        <div class="input-field-grp">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Login') }}
                            </button>
                        </div>

                        @if (Route::has('password.request'))
                            <a class="btn btn-link forgot-password" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
