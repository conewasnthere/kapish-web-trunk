@inject('user', 'App\Http\Controllers\UsersController')

@extends('layouts.app')

@section('title', $server->name)

@section('meta')
<meta property="og:title" content="Servers - {{ config('app.name') }}">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current(); }}">
<meta property="og:image" content="/images/logos/small.png">
<meta property="og:description" content="These are some publicly available servers on {{ config('app.name') }}. They are all hosted by our users.">
<meta name="theme-color" content="#0000FF">
@endsection

@section('content')
<div class="container">
    @if (session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
    @endif

    <div class="justify-content-center">
        @if ($server->version == "2010")
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i> <b>Please note that 2010 is considered dangerous.</b>
            </div>
        @endif
        <div class="card shadow-sm">
            <div class="card-header">
                <div class="row">
                    <div class="col mt-1">
                        {{ config('app.name') }} Server
                    </div>
                    @if (Auth::check())
                        @if (Auth::user()->id == $server->user->id || Auth::user()->admin)
                        <div class="col d-flex justify-content-end">
                            <div class="dropdown">
                                <a class="btn btn-light btn-sm border dropdown-toggle" href="#" role="button" id="settingsDropdown{{ $server->uuid }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-h"></i>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="settingsDropdown{{ $server->uuid }}">
                                    <a class="dropdown-item" href="{{ route('servers.configure', $server->uuid) }}"><i class="fas fa-cog mr-1"></i>Configure</a>
                                    <button data-toggle="modal" data-target="#deleteModal" class="dropdown-item" style="color: red;" type="submit"><i class="far fa-trash-alt mr-1"></i>Delete</button>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endif
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mt-2"><img class="img-fluid rounded" data-tadah-thumbnail-id="{{ $server->uuid }}" data-tadah-thumbnail-type="place-thumbnail" src="{{ asset('images/thumbnail/blank_place.png') }}" alt="{{ $server->name }} Thumbnail"></p>
                    </div>
                    <div class="col">
                        <h1 class="mb-0">{{ $server->name }}</h1>
                        <h5 class="text-muted">
                            By <a href="{{route('users.profile', $server->user->id)}}">
                                <div class="d-inline-block position-relative text-center h-100">
                                    <img class="headshot-bg position-relative rounded-circle border mx-1" data-tadah-thumbnail-id="{{ $server->creator }}" src="{{ asset('images/thumbnail/blank.png') }}" data-tadah-thumbnail-type="user-headshot" width="30">
                                </div>
                                {{$server->user->username}}
                            </a>
                        </h5>
                        @if (Cache::has('server_online' . $server->id))
                        <span class="badge badge-pill badge-success"><i style="font-size: 50%" class="fas fa-circle align-middle mr-1"></i><b>Online ({{ sizeof(Cache::get('server_online' . $server->id, 0)) }}/{{ $server->maxplayers }})</b></span>
                        @elseif ($server->version == "2016" && $server->user->username == "stan")
                        <span class="badge badge-pill badge-success"><i style="font-size: 50%" class="fas fa-circle align-middle mr-1"></i><b>Always Online</b></span>
                        @else
                        <span class="badge badge-pill badge-secondary"><i style="font-size: 50%" class="fas fa-circle align-middle mr-1"></i><b>Offline</b></span>
                        @endif
                        <span class="badge badge-pill badge-secondary"><b>{{$server->version}}</b></span>
                        @if (\App\Models\User::find($server->creator)->verified_hoster)
                        <span class="badge badge-pill badge-primary"><b><i class="fas fa-badge-check mr-1"></i>Verified</b></span>
                        @endif

                        <div class="d-flex border-top py-3 mt-3 mb-2">
                            @if (Auth::check())
                                @if ($server->version == "2016")
                                    <a id="join-server-{{ $server->uuid }}" onclick="tadah.joinServer('{{ $server->uuid }}', '{{ $server->version }}')" class="btn btn-block py-3 btn-success btn-lg"><i class="fas fa-play"></i></a>
                                    @else
                                <a id="join-server-{{ $server->uuid }}" onclick="tadah.joinServer('{{ $server->uuid }}', '{{ $server->version }}')" class="btn btn-block py-3 btn-success btn-lg @if (!Cache::has('server_online' . $server->id)) disabled @elseif (sizeof(Cache::get('server_online' . $server->id, 0)) >= $server->maxplayers)) disabled @endif"><i class="fas fa-play"></i></a>
                                @endif
                                @else
                                @if ($server->allow_guests)
                                    <a id="join-server-{{ $server->uuid }}" onclick="tadah.joinServer('{{ $server->uuid }}', '{{ $server->version }}')" class="btn btn-block py-3 btn-success btn-lg @if (!Cache::has('server_online' . $server->id)) disabled @elseif (sizeof(Cache::get('server_online' . $server->id, 0)) >= $server->maxplayers)) disabled @endif"><i class="fas fa-play"></i></a>
                                @else
                                    <p class="text-small text-muted">Sorry, this server doesn't allow Guests.</p>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm my-3">
            <div class="card-body">
                @if(!empty($server->description))
                    <div class="border rounded p-2 text-muted">{{$server->description}}</div>
                @endif
                <hr>
                <div class="row d-flex justify-content-center my-3">
                    <div class="col-md-2 text-center">
                        <b>Playing</b>
                        <div class="d-block text-muted">
                            @if (Cache::has('server_online' . $server->id))
                            {{ sizeof(Cache::get('server_online' . $server->id, 0)) }}
                            @else
                            0
                            @endif
                        </div>
                    </div>
                    <div class="col-md-2 my-2 my-md-0 text-center">
                        <b>Place Visits</b>
                        <div class="d-block text-muted">
                            {{number_format($server->visits)}}
                        </div>
                    </div>
                    <div class="col-md-2 my-2 my-md-0 text-center">
                        <b>Created</b>
                        <div class="d-block text-muted">
                            {{ date('m/d/Y', strtotime($server->created_at)) }}
                        </div>
                    </div>
                    <div class="col-md-2 my-2 my-md-0 text-center">
                        <b>Updated</b>
                        <div class="d-block text-muted">
                            {{ $server->updated_at->ago() }}
                        </div>
                    </div>
                    <div class="col-md-2 my-2 my-md-0 text-center">
                        <b>Max Players</b>
                        <div class="d-block text-muted">
                            {{ $server->maxplayers }}
                        </div>
                    </div>
                </div>
                <hr>
            </div>
        </div>

        @if (Cache::has('server_online' . $server->id))
        <div class="card shadow-sm my-3">
            <div class="card-header">
                Players
            </div>
            <div class="card-body">
                @foreach(Cache::get('server_online' . $server->id) as $user)
                    @if ($user == "guest")
                    <img class="img-responsive" style="object-fit: contain;" src="{{ asset('images/thumbnail/blank.png') }}" data-tadah-thumbnail-type="user-thumbnail" data-tadah-thumbnail-id="1" alt="A Friendly Guest" data-toggle="tooltip" data-placement="top" title="A Friendly Guest" width="100" height="100">
                    @else
                    <a href="{{ route('users.profile', $user->id) }}"><img class="img-responsive" style="object-fit: contain;" src="{{ asset('images/thumbnail/blank.png') }}" data-tadah-thumbnail-type="user-thumbnail" data-tadah-thumbnail-id="{{ $user->id }}" alt="{{ $user->username }}" data-toggle="tooltip" data-placement="top" title="{{ $user->username }}" width="100" height="100"></a>
                    @endif
                @endforeach
            </div>
        </div>
        @endif

        @if (Auth::check())
            @if ($server->user->id == Auth::user()->id)
                <div class="card card-body shadow-sm">
                    <h4>This is your server.</h4>
                    @if ($server->version == "2016")
                    <p class="mb-0">
                        At {{ config('app.name') }}, you don't need to host 2016. We host it for you!
                    </p>
                    @else
                    <p class="mb-0">
                        To host it, open {{ config('app.name') }} and paste this into the command bar:<br>
                        <code id="host-script" style="cursor: pointer">dofile('http://{{ request()->getHttpHost() }}/server/host/{{ $server->secret }}')</code>
                        <br class="mb-1"><small class="text-muted">You can click the script to copy it to your clipboard.</small>
                    </p>
                    @endif
                </div>
            @endif
        @endif
    </div>
    @if (Auth::check())
        @if(Auth::user()->id == $server->user->id || Auth::user()->admin)
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="row text-center justify-content-center">
                            <div class="justify-content-center">
                                <p><img class="img-responsive rounded" data-tadah-thumbnail-id="{{ $server->uuid }}" data-tadah-thumbnail-type="place-thumbnail" src="{{ asset('images/thumbnail/blank_place.png') }}" height="100" alt="{{ $server->name }} Thumbnail"></p>
                                <p class="m-0 p-0">Are you sure you want to delete {{ $server->name }}?</p>
                                <p class="text-danger m-0 p-0"><i class="fas fa-exclamation-triangle mr-1"></i>This action cannot be reversed.</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <form method="POST" action="{{ route('servers.delete', $server->uuid) }}">
                            @csrf
                            <button class="btn btn-danger shadow-sm" type="submit">Delete</button>
                        </form>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endif
</div>
<script src="{{ asset('/js/servers.js') }}?t=1"></script>
@endsection
