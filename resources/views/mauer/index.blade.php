@inject('user', 'App\Http\Controllers\UsersController')

@extends('layouts.mauer')

@section('title', 'Die Mauer')

@section('content')
<div class="container">
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif

    <div class="d-flex justify-content-between">
        <h1>Die Mauer</h1>
        @if (Auth::check() && (Auth::user()->scribbler || Auth::user()->isAdmin()))
            <div class="d-flex align-items-center">
                <a class="btn btn-success" href="{{ route('mauer.scribble') }}">
                    <i class="fas fa-pencil me-1"></i>
                    Scribble
                </a>
            </div>
        @endif
    </div>
    <p>A place for trusted people to gossip; publicly. We (as in {{ config('app.name') }}) claim no responsibility for anything posted here, as there will be little to no moderation.</p>
    <hr>
    @foreach ($scribbles as $scribble)
        <div class="card card-body" id="{{ $scribble->id }}">
            <p>
                <a href="#{{ $scribble->id }}">#{{ $scribble->id }}</a>
                <span class="mx-2">|</span>
                @if ($scribble->anonymous)
                    <b>Anonymous</b>
                @else
                    <a class="text-secondary" href="{{ route('users.profile', $scribble->user_id) }}">
                        <img src="{{ asset('images/thumbnail/blank.png') }}" data-tadah-thumbnail-id="{{ $scribble->user_id }}" data-tadah-thumbnail-type="user-headshot" class="rounded-circle border mr-1" width="25">{{ \App\Models\User::find($scribble->user_id)->username }}
                    </a>
                @endif
                <span class="mx-2">|</span>
                {{ (new DateTime($scribble->created_at, new DateTimeZone(config('app.timezone'))))->format('Y-m-d g:i A ') . config('app.timezone') }}
                <span class="mx-2">|</span>
                <i>{{ $scribble->title }}</i>
                @if (Auth::check() && ((Auth::user()->scribbler && Auth::user()->id == $scribble->user_id) || Auth::user()->isAdmin()))
                    <a href="{{ route('mauer.edit', $scribble->id) }}" class="ml-1">(edit)</a>
                @endif
            </p>

            @parsedown($scribble->body)
        </div>
        <br>
    @endforeach
    <div class="d-flex justify-content-center">
        {{ $scribbles->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection