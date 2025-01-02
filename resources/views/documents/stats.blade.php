@extends('layouts.app')

@section('title', 'Statistics')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header">{{ config('app.name') }} Stats</div>
        <div class="card-body">
            <p class="mb-0">This page is cached every 10 minutes.</p>
            <ul>
                <li>There are <b>{{ $userCount }} users</b>, <b>{{ $itemCount }} items</b>, and <b>{{ $serverCount }} servers</b>.</li>

                <li>On the forums, there are <b>{{ $threadCount }} threads</b> and <b>{{ $postCount }} replies</b>, totaling up to <b>{{ $threadCount + $postCount }} posts</b>.</li>
                <li>Our latest user is <b><a href="{{ route('users.profile', $latestUser->id) }}">{{ $latestUser->username }}</a></b>, say hello!</li>
              @if( empty($mostVisited->uuid) )
              <li>There are no servers at the moment.</li>
              @else
                <li>The most visited server is <b><a href="{{ route('servers.server', $mostVisited->uuid) }}">{{ $mostVisited->name }}</a></b> by <a href="{{ route('users.profile', $mostVisited->user->id) }}">{{ $mostVisited->user->username }}</a>, check it out.</li>
             @endif
            </ul>
            <hr>
            <p class="mb-0">The top 15 richest users are:</p>
            <ul>
                @foreach ($richest as $user)
                    <li><b>{{ $user->username }}</b> with <b>{{ $user->money }}</b> {{ config('app.currency_name_multiple') }}</li>
                @endforeach
            </ul>
            <hr>
            <p class="mb-0">The top 15 poorest users are:</p>
            <ul>
                @foreach ($poorest as $user)
                    <li><b>{{ $user->username }}</b> with <b>{{ $user->money }}</b> {{ config('app.currency_name_multiple') }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection
