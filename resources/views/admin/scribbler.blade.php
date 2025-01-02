@inject('user', 'App\Http\Controllers\UsersController')

@extends('layouts.admin')

@section('title', 'Toggle Hoster')

@section('content')
<div class="container">
    <h1><b>Toggle Scribbler</b></h1>
    <p>Toggles Scribbler on the user. If enabled, they get to scribble on <a href="{{ route('mauer') }}">Die Mauer.</a></p>
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
    <form method="POST" action="{{ route('admin.toggle_scribbler') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" class="form-control" id="username" placeholder="Username">
        </div>
        <button type="submit" class="btn btn-info shadow-sm">Toggle Scribbler</button>
    </form>
</div>
@endsection