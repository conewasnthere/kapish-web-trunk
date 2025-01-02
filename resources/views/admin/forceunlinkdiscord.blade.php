@inject('user', 'App\Http\Controllers\UsersController')

@extends('layouts.admin')

@section('title', 'Unlink Discord')

@section('content')
<div class="container">
    <h1><b>Force Unlink Discord</b></h1>
    <p>Forces the unlink of a user's Discord account from their Tadah account. Used for support tickets.</p>
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
    <form method="POST" action="{{ route('admin.forceunlinkdiscord') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" class="form-control" id="username" placeholder="Username">
        </div>
        <button type="submit" class="btn btn-info shadow-sm">Force Unlink Discord</button>
    </form>
</div>
@endsection