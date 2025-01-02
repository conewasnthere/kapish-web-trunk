@inject('thumbnail', \App\Http\Cdn\Thumbnail::class)
@extends('layouts.app')

@section('title', $user->username . '\'s Profile')

@section('meta')
<meta property="og:title" content="{{ $user->username }}'s Profile - {{ config('app.name') }}">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current(); }}">
<meta property="og:image" content="/images/logos/small.png">
<meta property="og:description" content="">
<meta name="theme-color" content="#0000FF">
@endsection

@section('content')
<div class="container">
    @if ($ban)
    <div class="alert alert-danger" role="alert">
        This user is banned. Reason: {{ $ban->ban_reason }}
    </div>
    @endif
    <div class="col-md p-0 mb-3">
        <div class="card card-body text-left shadow-sm">
            <div class="d-block d-md-inline-block text-md-left text-center position-relative">
                <div class="position-relative d-inline-block h-100">
                    <img class="position-relative img-fluid rounded-circle headshot-bg" style="max-height: 8rem" data-tadah-thumbnail-type="user-headshot" data-tadah-thumbnail-id="{{ $user->id }}" src="{{ $thumbnail::static_image('blank.png') }}">
                    <div class="{{(Cache::has('last_online' . $user->id) ? 'bg-primary' : 'bg-secondary')}} status-border position-absolute shadow-lg" style="right: 0; bottom: 0; height: 30px; width: 30px; border-radius: 50%; display: inline-block"></div>
                </div>
                <div class="d-inline-block py-2 py-md-0 align-middle">
                    @if($user->isAdmin())
                        <h2 class="font-weight-bold d-block d-md-inline-block text-center text-md-left text-danger px-3"><i class="fas fa-shield mr-2"></i>{{ $user->username }}</h2>
                    @elseif($user->donator && !$user->isEventStaff())
                        <h2 class="font-weight-bold d-block d-md-inline-block text-center text-md-left text-donator px-3"><i class="fas fa-gem mr-2"></i>{{ $user->username }}</h2>
                    @elseif($user->booster && !$user->isEventStaff())
                        <h2 class="font-weight-bold d-block d-md-inline-block text-center text-md-left text-booster px-3"><i class="fas fa-hard-hat mr-2"></i>{{ $user->username }}</h2>
                    @elseif($user->verified_hoster && !$user->isEventStaff())
                        <h2 class="font-weight-bold d-block d-md-inline-block text-center text-md-left text-info px-3"><i class="fas fa-badge-check mr-2"></i>{{ $user->username }}</h2>
                    @elseif($user->isEventStaff() || $user->isModerator())
                        <h2 class="font-weight-bold d-block d-md-inline-block text-center text-md-left text-success px-3"><i class="fas fa-user-shield mr-2"></i>{{ $user->username }}</h2>
                    @else
                        <h2 class="font-weight-bold d-block d-md-inline-block text-center text-md-left px-3">{{ $user->username }}</h2>
                    @endif
                    @if (!empty($user->blurb))
                        <h5 class="text-muted d-block text-left px-3">
                            <i>&ldquo;{{ \Illuminate\Support\Str::limit($user->blurb, 60) }}&rdquo;</i>
                        </h5>
                    @endif
                </div>
            </div>
            <div class="d-flex justify-content-end">
                @if (Auth::check())
                    <div class="row">
                        @if (Auth::user()->isAdmin() && Auth::user()->id != $user->id && !$user->isAdmin())
                            <div class="col-auto px-1">
                                <a class="btn btn-secondary" style="display:inline-block;" href="{{ route('admin.alts', $user->id) }}"><i class="fas fa-eye me-1" aria-hidden="true"></i>View Alts</a>
                            </div>
                            <div class="col-auto px-1">
                                <a class="btn btn-danger" style="display:inline-block;" href="{{ route('admin.banuser', ['username' => $user->username]) }}"><i class="fas fa-hammer me-1" aria-hidden="true"></i>Ban</a>
                            </div>
                        @endif
                        @if($user->id != Auth::user()->id)
                        @php
                            $friendship = \App\Models\Friendship::where(['requester_id' => $user->id, 'receiver_id' => Auth::user()->id])->first() ?: \App\Models\Friendship::where(['requester_id' => Auth::user()->id, 'receiver_id' => $user->id])->first();
                        @endphp
                        @if(!$friendship || !$friendship->areFriends())
                        <div class="col-auto ps-1">
                            <a id="toggleFriendshipBtn" {{!$friendship || $friendship->areFriends() ? 'onclick=tadah.addFriendButton(' . $user->id .')' : ''}} class="btn {{!$friendship || $friendship->areFriends() ? 'btn-primary' : 'btn-secondary disabled'}} shadow-sm">{{!$friendship || $friendship->areFriends() ? 'Add Friend' : 'Pending'}}</a>
                        </div>
                        @else
                        <div class="col-auto ps-1">
                            <a id="toggleFriendshipBtn" onclick="tadah.removeFriendButton({{$user->id}})" class="btn btn-danger shadow-sm">Remove Friend</a>
                        </div>
                        @endif
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 text-center">
            <div class="card shadow-sm">
                <div class="card-body">
                    @if (Cache::has('last_online' . $user->id))
                    <p class="text-center text-primary">[ Online ]</p>
                    @else
                    <p class="text-center text-muted">[ Offline ]</p>
                    @endif

                    <div class="position-relative my-2" id="thumbnail-container">
                        <button class="position-absolute btn btn-outline-secondary disabled" disabled  id="toggle-profile-3d" style="bottom: 5; right: 0;">3D</button>
                        <img width="250" height="250" class="img-fluid" data-tadah-thumbnail-type="user-thumbnail" data-tadah-thumbnail-id="{{ $user->id }}" src="{{ $thumbnail::static_image('blank.png') }}" alt="{{ $user->username }}">
                        <div class="d-none" id="three-dee-spinner">
                            <div class="text-center d-inline-flex align-items-center justify-content-center" style="height: 250px; width: 250px">
                                <div class="spinner-border text-dark" role="status" style="width: 50px; height: 50px;">
                                    <span class="sr-only">Loading 3D Thumbnail...</span>
                                </div>
                            </div>
                        </div>
                        <button data-toggle="modal" data-target="#wearingItems" class="position-absolute btn btn-outline-primary" style="bottom: 0; right: 0;">Show Items</button>
                    </div>

                    @if (!empty($user->blurb))
                    <div class="overflow-auto card card-body p-2 text-center" style="max-width: 100%; max-height: 125px; text-align: left;">{{ $user->blurb }}</div>
                    @endif

                    <hr>
                    @php
                        $visits = 0;
                        foreach($servers as $server)
                        {
                            $visits = $visits + $server->visits;
                        }
                    @endphp
                    <div class="row d-flex justify-content-center">
                        <div class="col-md-3 my-2 my-md-0">
                            <b>Joined</b>
                            <div class="text-muted d-block">
                                {{ date('m/d/Y', strtotime($user->joined)) }}
                            </div>
                        </div>
                        <div class="col-md-3 my-2 my-md-0">
                            <b>Last Online</b>
                            <div class="text-muted d-block">
                                {{ $user->last_online->ago() }}
                            </div>
                        </div>
                        <div class="col-md-3 my-2 my-md-0">
                            <b>Friends</b>
                            <div class="text-muted  d-block">
                                {{ $user->friends()->count() }}
                            </div>
                        </div>
                        <div class="col-md-3 my-2 my-md-0">
                            <b>Place Visits</b>
                            <div class="text-muted  d-block">
                                {{ $visits }}
                            </div>
                        </div>
                    </div>
                    <hr>

                    @if ($user->isAdmin())
                    <small class="d-block font-weight-bold font-weight-bold text-danger user-select-none"><i class="fas fa-user-shield mr-1"></i>This user is an administrator.</small>
                    @endif

                    @if ($user->isModerator())
                    <small class="d-block font-weight-bold text-success user-select-none"><i class="fas fa-user-shield mr-1"></i>This user is a moderator.</small>
                    @endif

                    @if ($user->donator)
                    <small class="d-block font-weight-bold text-donator user-select-none"><i class="fas fa-gem mr-1"></i>This user is a donator.</small>
                    @endif

                    @if ($user->booster)
                    <small class="d-block font-weight-bold text-booster user-select-none"><i class="fas fa-hard-hat mr-1"></i>This user is a Booster Club member.</small>
                    @endif

                    @if (Auth::user()->isAdmin())
                    <small class="d-block font-weight-bold text-secondary">
                        <i class="fab fa-discord mr-1"></i>
                        @if ($user->discord_id) {{ $user->discord_id }} @else This user has not linked a Discord account. @endif
                    </small>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6 my-2 my-md-0">
            <div class="card shadow-sm">
                <div class="text-center pt-3 px-2">
                    Servers
                    <hr class="mt-3 m-0">
                </div>
                <div class="card-body">
                    @if ($servers->count() > 0 )
                    @foreach ($servers as $server)
                    <div class="games mt-2">
                        <div class="game mt-0 mb-1 text-trunicate">
                            <a class="btn btn-primary rounded btn-block text-left py-1 text-white" data-toggle="collapse" href="#game-{{ $server->uuid }}">
                                <i class="far fa-gamepad mr-1" aria-hidden="true"></i>{{ $server->name }}
                            </a>
                        </div>
                        <div class="collapse" id="game-{{ $server->uuid }}">
                            <div class="col px-0 mb-3">
                                <div class="card card-body d-flex flex-column shadow-sm h-100">
                                    <a href="{{ route('servers.server', $server->uuid) }}"><img class="card-img-top rounded" style="max-height: 250px;" data-tadah-thumbnail-id="{{ $server->uuid }}" data-tadah-thumbnail-type="place-thumbnail" src="{{ asset('images/thumbnail/blank.png') }}" alt="{{$server->name}} thumbnail"></a>
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <div class="card-title mb-0 h-auto">
                                            <h4 class="d-inline-block m-0">
                                                <a class="text-secondary" href="{{ route('servers.server', $server->uuid) }}">{{ $server->name }}</a>
                                            </h4>
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
                                    <div class="row mt-2">
                                        <div class="col w-75 pr-0">
                                            <a id="join-server-{{ $server->uuid }}" onclick="tadah.joinServer('{{ $server->uuid }}', '{{ $server->version }}')" class="btn-block btn btn-success mt-auto @if (!Cache::has('server_online' . $server->id)) disabled @elseif (sizeof(Cache::get('server_online' . $server->id, 0)) >= $server->maxplayers)) disabled @endif shadow-sm"><i class="fas fa-play mr-1"></i>Play</a>
                                        </div>
                                        @if(Auth::user()->id == $server->creator || Auth::user()->admin == 1)
                                            <div class="col-auto">
                                                <a id="configure" href="/server/{{$server->uuid}}/configure" class="btn btn-primary mt-auto shadow-sm"><i class="fas fa-cog mr-1"></i>Configure</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @else
                    This user has no servers.
                    @endif
                </div>
            </div>
            <div class="card shadow-sm my-3">
                <div class="pt-3 px-2">
                    <div class="row d-flex justify-content-center">
                        <div class="col"></div>
                        <div class="col text-center">
                            Friends
                        </div>
                        <div class="col text-right">
                            <a class="font-weight-bold ml-auto px-2" href="{{route('users.friends', $user->id)}}">See All <i class="fas fa-angle-right"></i></a>
                        </div>
                    </div>
                    <hr class="mt-3 m-0">
                </div>
                <div class="card-body">
                    @if($friends->count() > 0)
                    <div class="row">
                        @foreach($friends as $friend)
                            @php
                                $friendUser = ($friend->receiver_id == $user->id ? $friend->requester : $friend->receiver)
                            @endphp
                            <div class="col-6 col-md-3 text-center">
                                <div class="col-auto p-0">
                                    <a href="/users/{{$friendUser->id}}/profile">
                                        <div class="position-relative d-inline-block h-100">
                                            <img class="position-relative img-fluid rounded-circle headshot-bg" style="max-height: 6.5rem" data-tadah-thumbnail-type="user-headshot" data-tadah-thumbnail-id="{{ $friendUser->id }}" src="{{ $thumbnail::static_image('blank.png') }}">
                                            <div class="{{(Cache::has('last_online' . $friendUser->id) ? 'bg-primary' : 'bg-secondary')}} status-border position-absolute shadow-lg" style="right: 0; bottom: 0; height: 25px; width: 25px; border-radius: 50%; display: inline-block"></div>
                                        </div>
                                    </a>
                                </div>
                                <div class="d-flex justify-content-center pt-2">
                                    <a class="font-weight-bold" href="{{route('users.profile', $friendUser->id)}}">{{$friendUser->username}}</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @else
                        This user has no friends.
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="wearingItems" tabindex="-1" role="dialog" aria-labelledby="wearingItemsLbl">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title" id="wearingItemsLbl">
                        <img class="img-responsive rounded-circle border shadow-sm mr-1" style="object-fit: contain;" width=30 height=30 data-tadah-thumbnail-id="{{ $user->id }}" data-tadah-thumbnail-type="user-headshot" src="{{ asset('images/thumbnail/blank.png') }}"  alt="{{ $user->username }}">
                        <b>{{$user->username}}</b> is currently wearing
                    </p>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="row mx-0 py-2">
                @if($wornItems->count() > 0)
                @foreach($wornItems as $item)
                        <div class="col-3 pb-3 px-2">
                            <div class="card card-body shadow-sm p-2">
                                <a href="{{ route('item.view', $item->id) }}" class="text-decoration-none">
                                    <img data-tadah-thumbnail-type="item-thumbnail" data-tadah-thumbnail-id="{{ $item->id }}" src="{{ $thumbnail::static_image('blank.png') }}" alt="{{ $item->name }} {{ __('thumbnail') }}" class="card-img-top p-2" width="128" style="border-radius: .75rem; max-height: 128px;">
                                    <div class="mt-1 text-truncate">{{ $item->name }}</div>
                                </a>
                            </div>
                        </div>
                @endforeach
                @else
                    <p class="p-0 m-0 w-100 d-flex justify-content-center text-muted"> {{ $user->username }} is not wearing anything at the moment.</p>
                @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
