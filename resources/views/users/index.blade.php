@extends('layouts.app')

@section('title', 'Users')

@section('meta')
<meta property="og:title" content="{{ config('app.name') }} - Users">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current(); }}">
<meta property="og:image" content="/images/logos/small.png">
<meta property="og:description" content="Take a look at all of the {{ config('app.name') }} community members.">
<meta name="theme-color" content="#0000FF">
@endsection

@section('content')
<div class="container">
    <div class="overflow-auto">
        @if(request()->get('search'))
            <h3 class="font-weight-bold">
                Results for <span class="text-muted">{{request()->get('search')}}</span>
            </h3>
        @else
            <h1 class="font-weight-bold">        
                Users
            </h1>
        @endif
        <hr>
        <form method="get">
            <div class="input-group">
                <input class="form-control" type="search" placeholder="Username" name="search" aria-label="Search">
                <span class="input-group-append"><button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button></span>
            </div>
        </form>

        @if ($users->count() > 0)
            <table class="mt-3 table">
                <thead>
                    <th scope="col" style="width: 5%">Character</th>
                    <th scope="col" style="width: 15%">Name</th>
                    <th scope="col" style="width: 10%">Online</th>
                    <th scope="col" style="width: 55%">Blurb</th>
                    <th scope="col" style="width: 15%">Last Online</th>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td class="align-middle">
                                <a href="{{ route('users.profile', $user->id) }}">
                                    <div class="position-relative d-inline-block h-100">										
                                    <img class="img-responsive rounded-circle shadow-sm headshot-bg" style="object-fit: contain;" width=50 height=50 data-tadah-thumbnail-id="{{ $user->id }}" src="{{ asset('images/thumbnail/blank.png') }}" data-tadah-thumbnail-type="user-headshot" alt="{{ $user->username }}">
                                        <div class="status-border-body {{(Cache::has('last_online' . $user->id) ? 'bg-primary' : 'bg-secondary')}} position-absolute shadow-lg" style="right: 0; bottom: 0; height: 16px; width: 16px; border-radius: 50%; display: inline-block"></div>
                                    </div>
                                </a>
                            </td>
                            <td class="align-middle">
                                @if ($user->isAdmin())
                                    <a href="{{ route('users.profile', $user->id) }}" class="text-danger font-weight-bold"><i class="fas fa-shield mr-1"></i>{{ $user->username }}</a>
				@elseif ($user->donator && !$user->isEventStaff())
                                    <a href="{{ route('users.profile', $user->id) }}" class="text-donator font-weight-bold"><i class="fas fa-gem mr-1"></i>{{ $user->username }}</a>
                                @elseif ($user->booster && !$user->isEventStaff())
                                    <a href="{{ route('users.profile', $user->id) }}" class="text-booster font-weight-bold"><i class="fas fa-hard-hat mr-1"></i>{{ $user->username }}</a>
                                @elseif($user->isEventStaff() || $user->isModerator())                        
                                    <a href="{{ route('users.profile', $user->id) }}" class="text-success font-weight-bold"><i class="fas fa-user-shield mr-1"></i>{{ $user->username }}</a>
                                @else
                                    <a href="{{ route('users.profile', $user->id) }}">{{ $user->username }}</a>
                                @endif
                            </td>
                            @if (Cache::has('last_online' . $user->id))
                                <td class="align-middle text-primary"><small><i style="font-size: 50%" class="fas fa-circle align-middle mr-1"></i><b>Online</b></small></td>
                            @else
                                <td class="align-middle text-muted"><small><i style="font-size: 50%" class="fas fa-circle align-middle mr-1"></i>Offline</small></td>
                            @endif
                            <td class="align-middle text-truncate" style="max-width: 75ch"><small>{{ $user->blurb }}</small></td>
                            <td class="align-middle text-muted">{{ $user->last_online->diffForHumans() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="text-center">
                <img src="/images/blobs/exhausted.png" class="img-fluid">
                <h2>Nothing found</h2>
                <p>Looks like there are no users to display for this query.</p>
            </div>
        @endif

        <div class="d-flex justify-content-center">
            {{ $users->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endsection
