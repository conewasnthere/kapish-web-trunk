@inject('user', 'App\Http\Controllers\UsersController')

@extends('layouts.admin')

@section('title', 'Unban')

@section('content')
<div class="container">
    <h1><b>Unban user</b></h1>
    <p>Unban a user from {{ config('app.name') }}. Usually used during moderation errors.</p>
    <hr>
    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session()->get('error') }}
        </div>
    @endif
    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif
    <form method="POST" action="{{ route('admin.unbanuser') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" class="form-control" id="username" placeholder="Username">
        </div>
        <button type="submit" class="btn btn-success shadow-sm"><i class="fas fa-user mr-1"></i>Unban User</button>
    </form>
</div>
@endsection