@extends('layouts.admin')

@section('title', $user->username . '\'s Alts')

@section('content')
<div class="container">
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif
    <h1><b>{{ $user->username }}'s Alts</b></h1>
    <p>Bad actors are usually spread out.</p>
    @if ($associatedUsers->count() > 0)
    <table class="table">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Username</th>
                <th scope="col">Key</th>
                <th scope="col">Joined</th>
                <th scope="col">Ban</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($associatedUsers as $associatedUser)
                <tr>
                    <th scope="row">{{ $associatedUser->id }}</th>
                    <td><a href="{{ route('users.profile', $associatedUser->id) }}">{{ $associatedUser->username }}</a></td>
                    <td>{{ $associatedUser->invite_key }}</td>
                    <td>{{ date('m/d/Y ', strtotime($associatedUser->joined)) }}</td>
                    <td><a href="{{ route('admin.banuser', ['username' => $associatedUser->username]) }}" class="btn btn-danger btn-sm shadow-sm"><i class="fas fa-gavel"></i></button></td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center">
        {{ $associatedUsers->links('pagination::bootstrap-4') }}
    </div>
    @else
    <hr>
    <h1>No alts found</h1>
    <p>Perhaps they're using a VPN if you're truly suspicious.</p>
    @endif
</div>
@endsection