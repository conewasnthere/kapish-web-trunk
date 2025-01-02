@inject('thumbnail', \App\Http\Cdn\Thumbnail::class)
@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="container">
    <div class="my-3">
        <div class="d-block d-md-inline-block text-md-left text-center position-relative" style="z-index: 1;">            
            <div class="position-relative d-inline-block h-100">										
                <img class="shadow-sm position-relative img-fluid rounded-circle border headshot-bg" style="max-height: 10rem" data-tadah-thumbnail-type="user-headshot" data-tadah-thumbnail-id="{{ Auth::user()->id }}" src="{{ $thumbnail::static_image('blank.png') }}">                    
            </div>
            <div class="d-inline-block py-2 py-md-0 align-middle">
                <h2 class="font-weight-bold d-block d-md-inline-block text-center text-md-left px-3">Ahoy, {{ Auth::user()->username }}!</h2>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-between">
		<h2 class="mb-0 font-weight-bold">Friends ({{Auth::user()->friends()->count()}})</h2>		
        <div class="align-self-center">
            <a class="font-weight-bold" href="{{route('my.friends')}}">See All <i class="fas fa-angle-right"></i></a>
        </div>
	</div>
    <hr>
    <div class="my-3">
        <div class="overflow-auto flex-nowrap row mx-0 justify-content-left">
            @if($friends->count() > 0)
            @foreach($friends as $friend)
            @php
                $friendUser = ($friend->receiver_id == Auth::user()->id ? $friend->requester : $friend->receiver)
            @endphp
            <div class="col-auto px-md-2 text-center">
                <a href="{{route('users.profile', $friendUser->id)}}">
                    <div class="position-relative d-inline-block h-auto">										
                        <img class="position-relative img-fluid rounded-circle headshot-bg" style="max-height: 6.5rem;" data-tadah-thumbnail-type="user-headshot" data-tadah-thumbnail-id="{{ $friendUser->id }}" src="{{ $thumbnail::static_image('blank.png') }}">
                        <div class="{{(Cache::has('last_online' . $friendUser->id) ? 'bg-primary' : 'bg-secondary')}} status-border position-absolute shadow-lg" style="right: 0; bottom: 0; height: 25px; width: 25px; border-radius: 50%; display: inline-block"></div>
                    </div>
                </a>
                <div class="d-flex justify-content-center pt-2">
                    <a class="font-weight-bold" href="{{route('users.profile', $friendUser->id)}}">{{$friendUser->username}}</a>
                </div>
            </div>
            @endforeach
            @else
                <div id="empty" class="col text-center">                
                    <img data-toggle="tooltip" data-placement="left" title=":)" src="/images/blobs/egg.png" class="img-fluid py-2" width=100>
                    <h2>No friends found.</h2>
                    <p class="text-muted">Seems like you have no friends.</p>
                </div>
            @endif
        </div>
    </div>
    <div class="d-flex justify-content-between">
		<h2 class="mb-0 font-weight-bold">Servers</h2>
        <div class="align-self-center">
            <a class="font-weight-bold" href="{{route('servers.index')}}">See All <i class="fas fa-angle-right"></i></a>
        </div>
	</div>
    <hr>
    <div class="col-md p-0">
        @if ($servers->count() > 0)
            <div class="row col-xs-12">
                @foreach ($servers as $server)                
                    <div class="col-lg-2 col-6 mb-3">
                        <div class="card card-body d-flex flex-column shadow-sm">
                            <a href="{{ route('servers.server', $server->uuid) }}"><img class="card-img-top rounded pb-2" style="max-height: 250px;" src="{{ asset('images/thumbnail/blank_place.png') }}" data-tadah-thumbnail-type="place-thumbnail" data-tadah-thumbnail-id="{{ $server->uuid }}" alt="{{$server->name}} thumbnail"></a>                            
                            <div class="d-flex justify-content-between">
                                <div class="card-title mb-0 h-auto">
                                    <h5 class="d-inline-block m-0">
                                        <a class="text-secondary" href="{{ route('servers.server', $server->uuid) }}">{{ \Illuminate\Support\Str::limit($server->name, 10) }}</a>
                                    </h5>
                                    <div class="d-block justify-content-start">									
                                        <span class="badge badge-secondary text-white shadow-sm badge-pill user-select-1">{{ $server->version }}</span>
                                        @if (Cache::has('server_online' . $server->id))
                                            <span class="badge badge-pill badge-success text-small"><i style="font-size: 50%" class="fas fa-circle align-middle mr-1"></i><b>Online ({{ sizeof(Cache::get('server_online' . $server->id, 0)) }}/{{ $server->maxplayers }})</b></span>
                                        @else
                                            <span class="badge badge-pill badge-secondary text-small"><i style="font-size: 50%" class="fas fa-circle align-middle mr-1"></i>Offline</span>
                                        @endif									
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>                
                @endforeach
            </div>
        @else
            <div class="text-center">
                <img src="/images/blobs/open_mouth.png" class="img-fluid py-2" width=100>
                <h2>No servers</h2>
                <p class="text-muted">Seems like no one's around.</p>
            </div>
        @endif
    </div>
</div>
@endsection