@extends('layouts.admin')

@section('title', 'Users')

@section('meta')
<meta property="og:title" content="{{ config('app.name') }} - Banland">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current(); }}">
<meta property="og:image" content="/images/logos/small.png">
<meta property="og:description" content="Where the souls of banned {{ config('app.name') }} members rest.">
<meta name="theme-color" content="#0000FF">
@endsection

@section('content')
<div class="container">
    <h1><b>The Banland</b></h1>
    <p><i>May these souls rest in peace. Or not.</i></p>
    <hr>
    <form method="get">
        <div class="input-group">
            <input class="form-control" type="search" placeholder="Username" name="search" aria-label="Search">
            <span class="input-group-append"><button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button></span>
        </div>
    </form>

    @if ($bans->count() > 0)
        <table class="mt-3 table">
            <thead>
                <th scope="col" style="width: 15%">Name</th>            
                <th scope="col" style="width: 55%">Ban reason</th>
                <th scope="col" style="width: 5%">Banned</th>
                <th scope="col" style="width: 15%">Banned until</th>
                <th scope="col" style="width: 20%">Undoer</th>
            </thead>
            <tbody>
                @foreach ($bans as $ban)
                    <tr>                        
                        <td class="align-middle">
                            <a href="{{ route('users.profile', $ban->user_id) }}">
                                <img data-tadah-thumbnail-id="{{ $ban->user_id }}" data-tadah-thumbnail-type="user-headshot" src="{{ asset('images/thumbnail/blank.png') }}" class="rounded-circle border mr-1" width="30">
                                {{ \App\Models\User::find($ban->user_id)->username; }}
                            </a>
                        </td>
                        <td class="align-middle text-truncate" style="max-width: 75ch"><small>{{ $ban->ban_reason }}</small></td>
                        <td class="align-middle {{ ($ban->banned ? 'text-danger' : 'text-success') }}" style="max-width: 75ch">{{ ($ban->banned ? "Yes" : "No") }}</td>
                        <td class="align-middle text-muted"><small>{{ $ban->banned_until->toDayDateTimeString() }}</small></td>
                        <td class="align-middle text-muted">
                            @if($ban->pardon_user_id)
                            <a href="{{ ($ban->pardon_user_id) ? route('users.profile', $ban->pardon_user_id) : '' }}">
                                <img src="{{ asset('images/thumbnail/blank.png') }}" data-tadah-thumbnail-type="user-headshot" data-tadah-thumbnail-id="{{ $ban->pardon_user_id }}" class="rounded-circle border mr-1" width="30">
                                {{ ($ban->pardon_user_id) ? \App\Models\User::find($ban->pardon_user_id)->username : '' }}
                            </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="text-center">
            <h2>Nothing found</h2>
            <p>Looks like there are no bans to display for this query.</p>
        </div>
    @endif

    <div class="d-flex justify-content-center">
        {{ $bans->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
