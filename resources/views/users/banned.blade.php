@extends('layouts.app')

@section('title', 'Banned')

@section('content')
<div class="container">
    @if ($ban)
        <h1><b>Banned</b></h1>
        <hr>
        <p>You've been banned from {{ config('app.name') }}.</p>
        <p>Reason: <code>{{ $ban->ban_reason }}</code></p>
        <p>Banned until <b>{{ date('m/d/Y', strtotime($ban->banned_until)) }}</b>.</p>
        @if ($ban->banned_until->isPast())
            <form method="post" enctype="multipart/form-data">
                @csrf

                <button class="btn btn-sm btn-primary shadow-sm">Reactivate</button>
            </form>
        @endif
    @else
        <h1>Hey!</h1>
        <p>You're not banned, and you shouldn't be here!</p>
    @endif
</div>
@endsection
