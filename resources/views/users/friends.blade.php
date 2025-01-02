@extends('layouts.app')

@section('title', 'Friends')

@section('content')
<div class="container">
    <h1 class="font-weight-bold">{{$user->username}}'s Friends</h1>
    <hr>
    <div class="container px-0">                
        <div id="friends-container" class="row">
            @if($friends->count() > 0)
            @foreach($friends as $friend)
            @php
                $friendUser = ($friend->receiver_id == $user->id ? $friend->requester : $friend->receiver)
            @endphp
            <div class="col-12 col-md-4 mb-3">
                <div class="card card-body">
                    <div class="row p-2">
                        <div class="d-inline-block position-relative h-100">										
                            <a href="/users/{{$friendUser->id}}/profile">
                                <img style="max-height: 6rem" class="position-relative rounded-circle border mx-1" data-tadah-thumbnail-id="{{ $friendUser->id }}" data-tadah-thumbnail-type="user-headshot" src="{{ asset('images/thumbnail/blank.png') }}">
                                <div class="{{Cache::has('last_online' . $friendUser->id) ? 'btn-primary' : 'btn-secondary'}} status-border position-absolute shadow-lg" style="right: 0; bottom: 0; height: 25px; width: 25px; border-radius: 50%; display: inline-block"></div>
                            </a>
                        </div>
                        <div class="d-block px-2">                 
                            <a href="/users/{{$friendUser->id}}/profile"><h4 class="font-weight-bold mb-0">{{$friendUser->username}}</h4></a>
                            <p class="text-muted">{{Cache::has('last_online' . $friendUser->id) ? 'Online' : 'Offline'}}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            @else
            <div class="w-100 text-center">              
                <img data-toggle="tooltip" data-placement="left" title=":)" src="/images/blobs/egg.png" class="img-fluid py-2" width=100>
                <h2>No friends found.</h2>
                <p class="text-muted">Seems like {{$user->username}} has no friends.</p>                
            </div> 
            @endif            
        </div>
    </div>
</div>

<template id="empty">  
    <div class="w-100 text-center">              
        <img src="/images/blobs/exhausted.png" class="img-fluid" width=100>
        <h2>No friends found.</h2>
        <p class="text-muted">Seems like you have no friends.</p>
    </div>
</template>

@endsection

@section('scripts')
<script>

</script>
@endsection