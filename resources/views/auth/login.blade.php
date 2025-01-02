@extends('layouts.app')

@section('title', 'Login')

@php
$landing = true;
@endphp

@section('content')
<div class="container align-self-center"> <!-- Fax -->
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-center">
                <img class="img-fluid" width="180" src="{{asset('/images/logos/small.png')}}">
            </div>
            <div class="justify-content-center">
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf <!-- {{ csrf_field() }} -->

                        <div class="form-group row d-flex justify-content-center">
                            <div class="col-md-6">
                                <input placeholder="{{ __('Email') }}" id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row d-flex justify-content-center">                            
                            <div class="col-md-6">
                                <input placeholder="{{ __('Password') }}" id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row d-flex justify-content-center">                            
                            <div class="col-md-6">
                                <input placeholder="{{ __('2FA Code') }}" id="twofa" type="password" class="form-control @error('twofa') is-invalid @enderror" name="twofa" autocomplete="current-twofa">

                                @error('twofa')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row d-flex justify-content-center">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary shadow-sm w-100">
                                    <i class="fas fa-sign-in-alt mr-1"></i>{{ __('Login') }}
                                </button>
                            </div>
                        </div>

                        <div class="form-group row d-flex justify-content-center">
                            <div class="col-md-6">
                                <div class="form-check d-inline-block">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                                <div class="float-right">
                                    @if (Route::has('password.request'))
                                        <a class="text-decoration-none" href="{{ route('password.request') }}">
                                            {{ __('I forgot my password') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
