@inject('user', 'App\Http\Controllers\UsersController')

@extends('layouts.admin')

@section('title', 'Toggle Booster')

@section('content')
<div class="container">
    <h1><b>Toggle Boosters Club</b></h1>
    <p>Toggles Booster Club on a user. They get their log-in bonus doubled, and they get more place slots. They also get a badge in-game on the player list.</p>
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
    <form method="POST" action="{{ route('admin.togglebooster') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" class="form-control" id="username" placeholder="Username">
        </div>
        <button type="submit" class="btn btn-info shadow-sm">Toggle Boosters Club</button>
    </form>
</div>
@endsection