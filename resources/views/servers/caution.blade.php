@extends('layouts.app')

@section('title', 'Caution')

@php
$landing = true;
@endphp

@section('content')
<div class="container align-self-center">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex align-items-center flex-column">
            <i class="far fa-exclamation-triangle fa-9x text-warning mb-3"></i>
            <h1 class="text-secondary mb-4">Caution</h1>

            <div class="card card-body border-0 rounded-lg shadow">
                <p>
                    As we gradually reopen servers, we would like to remind you all that online play is offered by third-party servers that are not owned, operated, or supervised by {{ config('app.name') }}.
                    <br><br>
                    Accordingly, please use a VPN if you believe that your IP address is sensitive information that can be leaked. Server hosts are able to see your IP address.
                    <br><br>
                    {{ config('app.name') }} does not claim responsibility for what happens on online play servers. Please stay safe. <b>By proceeding, you certify that you understand that {{ config('app.name') }} is not responsible for what happens on self-hosted servers.</b>
                </p>

                <form method="post" action="{{ route('servers.caution') }}">
                    @csrf

                    <div class="text-center">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="certify" id="certify" required>
                            <label class="form-check-label" for="certify">I understand that {{ config('app.name') }} is not responsible for what occurs on self-hosted servers, and that I take full responsibility for what happens to my IP address given that I have seen this warning</label>
                        </div>

                        <button class="btn btn-primary btn-lg" type="submit">Proceed</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
