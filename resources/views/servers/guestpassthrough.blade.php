@extends('layouts.app')

@section('title', 'Enable Guest Mode')

@php
$landing = true;
@endphp

@section('content')
<div class="container align-self-center">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex align-items-center flex-column">
            <i class="far fa-exclamation-triangle fa-9x text-warning mb-3"></i>
            <h1 class="text-secondary mb-4">Enable Guest Mode</h1>

            <div class="card card-body border-0 rounded-lg shadow">
                <p>
                    You are about to enable Guest Mode. Server joins carried out by guests are logged.
                    <br><br>
                    Server hosters have the ability to choose whether or not they want guests in their servers, so you may not be able to join most servers while logged out.
                </p>

                <form method="post" action="{{ route('servers.guest_passthrough') }}">
                    @csrf

                    <div class="text-center">
                        <button class="btn btn-primary btn-lg" type="submit">Enable</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
