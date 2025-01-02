@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Google Authenticator already set up') }}</div>

                <div class="card-body text-center">
                    <p>{{ __('Your two-factor authentication is already set up and active.') }}</p>
                    <p>{{ __('If you need to reset your two-factor authentication, please contact support.') }}</p>
                    <div>
                        <a href="/my/dashboard"><button class="btn btn-primary">{{ __('Go to Dashboard') }}</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
