@extends('layouts.admin')

@section('title', 'Server Joins')

@section('content')
<div class="container">
    <h1><b>Server Joins</b></h1>
    <p>Server join logs. Records if the joinscript was generated and if the player ever authenticated in the server.</p>
    @if ($gamejoins->count() > 0)
    <table class="table">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">User</th>
                <th scope="col">Server</th>
                <th scope="col">Generated</th>
                <th scope="col">Validated</th>
                <th scope="col">Created</th>
                <th scope="col">Updated</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($gamejoins as $gamejoin)
                <tr>
                    <th scope="row">{{ $gamejoin->id }}</th>
                    @if ($gamejoin->user)
                    <td><a href="{{ route('users.profile', $gamejoin->user->id) }}">{{ $gamejoin->user->username }}</a></td>
                    @else
                    <td>Guest User</td>
                    @endif
                    <td><a href="{{ route('servers.server', $gamejoin->server->id) }}">{{ $gamejoin->server->name }}</a></td>
                    <td>{{ $gamejoin->generated ? "Yes" : "No"}}</td>
                    <td>{{ $gamejoin->validated ? "Yes" : "No"}}</td>
                    <td>{{ date('m/d/Y ', strtotime($gamejoin->created_at)) }}</td>
                    <td>{{ date('m/d/Y ', strtotime($gamejoin->updated_at)) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center">
        {{ $gamejoins->links('pagination::bootstrap-4') }}
    </div>
    @else
    <hr>
    <h1>No game joins found</h1>
    <p>Nobody has attempted to join any game yet.</p>
    @endif
</div>
@endsection