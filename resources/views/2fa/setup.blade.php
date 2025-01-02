@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Set up Google Authenticator') }}</div>

                <div class="card-body text-center">
                    <p>{{ __('Set up your two factor authentication by scanning the barcode below. Alternatively, you can use the code') }} {{ $secret }}</p>
                    {!! $QR_Image !!}
                    <p>{{ __('You must set up your Google Authenticator app before continuing. You will be unable to login otherwise') }}</p>
                    <div>
                        <a href="/2fa/complete/"><button class="btn btn-primary">{{ __('Complete Registration') }}</button></a>
                        <small class="form-text text-muted">{{ __('Note: If you lose your authentication, you will not be able to sign back in and you will need to contact support.') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
