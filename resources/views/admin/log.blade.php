@extends('layouts.admin')

@section('title', 'Action Log')

@section('content')
<div class="container">
    <h1><b>Action Log</b></h1>
    <p>Records every action an administrator has taken on Tadah. Think something is missing from these logs? Ask a developer to add it.</p>
    @if ($actions->count() > 0)
    <table class="table">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Admin</th>
                <th scope="col">Action</th>
                <th scope="col">Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($actions as $action)
                <tr @class(['bg-warning' => $action->show_danger])>
                    <th scope="row">{{ $action->id }}</th>
                    @if ($action->user)
                        <td><a href="{{ route('users.profile', $action->user->id) }}">{{ $action->user->username }}</a></td>
                    @else
                        <td>Unknown User</td>
                    @endif
                    <td>{{ $action->action }}</td>
                    <td>{{ $action->updated_at->toDayDateTimeString() }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center">
        {{ $actions->links('pagination::bootstrap-4') }}
    </div>
    @else
    <hr>
    <div class="text-center">
        <h1>No actions found</h1>
        <p>You really should start doing things...</p>
    </div>
    @endif
</div>
@endsection