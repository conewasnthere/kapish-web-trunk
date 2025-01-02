@extends('layouts.app')

@section('title', __('Invite Keys'))

@section('content')
<div class="container">
    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session()->get('error') }}
        </div>
    @endif

    <div class="d-flex justify-content-between">
        <h1 class="mb-0">{{ __('Invite Keys') }}</h1>
        @if (config('app.users_create_invite_keys'))
        <div class="d-flex align-items-center">
            <button data-toggle="modal" data-target="#keyPurchaseModal" class="btn btn-lg btn-success shadow-sm" type="submit"><img src="/images/dahllor_white.png" width="20" height="20"> Create Key</button>
        </div>
        @endif
    </div>

    <br>

    <p>{{ config('app.user_maximum_keys_in_window') }} keys can be created every {{ config('app.user_invite_key_cooldown') }} days. Creating a key costs {{ config('app.user_invite_key_cost') }} {{ config('app.currency_name_multiple') }}. You also need a linked Discord account.</p>

    <table class="table">
        <thead>
            <tr>
                <th scope="col">Key</th>
                <th scope="col">Created</th>
                <th scope="col">Updated</th>
                <th scope="col">Uses</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($inviteKeys as $inviteKey)
                <tr>
                    <td><code>{{ $inviteKey->token }}</code></td>
                    <td>{{ date('m/d/Y ', strtotime($inviteKey->created_at)) }}</td>
                    <td>{{ date('m/d/Y ', strtotime($inviteKey->updated_at)) }}</td>
                    <td>{{ $inviteKey->uses }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if (config('app.users_create_invite_keys'))
    <div class="modal fade" id="keyPurchaseModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row text-center justify-content-center">                        
                        <div class="justify-content-center">
                            <p class="mb-0">Are you sure you want to create an invite key? This costs<img src="/images/currency.png" width="20" height="20" class="mx-1">{{ number_format(config('app.user_invite_key_cost')) }} {{ config('app.currency_name_multiple') }}.</p>
                            <p class="text-danger mt-0 mb-0"><b>Remember:</b> You will be held accountable for who you invite and what they do.</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">                                    
                    <form method="POST" action="{{ route('my.keys') }}">
                        @csrf
                        <button class="btn btn-success shadow-sm" type="submit">Purchase</button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="d-flex justify-content-center">
        {{ $inviteKeys->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
