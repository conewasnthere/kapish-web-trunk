@extends('layouts.app')

@section('title', 'Register')

@php
$landing = true;
@endphp

@section('content')
<div class="container align-self-center">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-center">
                <img class="img-fluid" width="180" src="{{asset('/images/logos/small.png')}}">
            </div>
            <div class="{{config('app.registration_enabled') ? '' : 'text-center'}} justify-content-center">
                <div class="card-body">
                    @if (config('app.registration_enabled'))
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="form-group row d-flex justify-content-center">                                
                                <div class="col-md-6">
                                    <input placeholder="{{ __('Username') }}" id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>

                                    @error('username')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row d-flex justify-content-center">                                
                                <div class="col-md-6">
                                    <input placeholder="{{ __('Email') }}" id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            @if (config('app.invite_keys_required'))
                            <div class="form-group row d-flex justify-content-center">                                
                                <div class="col-md-6">
                                    <input placeholder="{{ __('Invite Key') }}" id="invite_key" type="text" class="form-control @error('invite_key') is-invalid @enderror" name="invite_key" value="{{ old('invite_key') }}" required>
                                    <div class="form-text text-muted"><i class="fas fa-exclamation-triangle mr-1"></i>You have to be invited.</div>

                                    @error('invite_key')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            @endif

                            <div class="form-group row d-flex justify-content-center">                                
                                <div class="col-md-6">
                                    <input placeholder="{{ __('Password') }}" id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row d-flex justify-content-center">
                                <div class="col-md-6">
                                    <input placeholder="{{ __('Confirm Password') }}" id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>

                            @if (config('app.use_captcha'))
                                <div class="form-group row d-flex justify-content-center">
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-center">
                                            {!! HCaptcha::display() !!}
                                        </div>
                                        
                                        @error('h-captcha-response')
                                            <span class="help-block text-danger">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            @endif
                            
                            <div class="form-group row d-flex justify-content-center mb-0">
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary shadow-sm w-100">
                                        <i class="fas fa-user-plus mr-1"></i>{{ __('Register') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    @else
                        <h2>Registration closed</h2>
                        <p>Sorry, we're not taking new users at the moment. Check back in a bit.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

