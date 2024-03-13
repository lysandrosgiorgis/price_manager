@extends('layouts.empty')

@section('content')
<div class="container">
    <div class="row justify-content-center min-dvh-100 align-items-center">
        <div class="col-12 col-sm-8 col-md-11 col-lg-8">
            <div class="card shadow">
                <div class="card-body p-0">
                    <div class="row g-0">
                        <div class="col-md-6 py-4 py-md-5 px-3 bg-md-gray-400 d-flex align-items-center justify-content-center">
                            <h1 class="text-primary">{{ config('app.name', 'Laravel') }}</h1>
                        </div>
                        <div class="col-md-6 py-2 py-md-5 px-3">
                            <h2>{{ __('Login')}}</h2>
                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <div class="form-floating mb-3">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="{{ __('Email') }}" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                    <label for="email" class="col-form-label text-md-end">{{ __('Email') }}</label>
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                                    @enderror
                                </div>

                                <div class="form-floating ">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="{{ __('Password') }}" required autocomplete="current-password">
                                    <label for="password" class="col-form-label text-md-end">{{ __('Password') }}</label>
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                                    @enderror
                                </div>
                                <div class="text-end mb-1">
                                    @if (Route::has('password.request'))
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    @endif
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>

                                <button type="submit" class="btn btn-primary px-4">
                                    {{ __('Login') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
