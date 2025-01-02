@inject('user', 'App\Http\Controllers\UsersController')

@extends('layouts.admin')

@section('title', 'Force Wear Item')

@section('content')
<div class="container">
    <h1><b>Force Wear Item</b></h1>
    <p>Force an user to wear any item on {{ config('app.name') }}. It will also automatically regenerate the user's thumbnail. Efficient torture method.</p>
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
    <form method="POST" action="{{ route('admin.forcewearitem') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" class="form-control" id="username" placeholder="Username">
        </div>
        <div class="form-group">
            <label for="itemid">Item ID</label>
            <input type="number" onwheel="this.blur()" name="itemid" class="form-control" id="itemid" placeholder="Item ID">
        </div>
        <div class="form-check">
            <input class="form-check-input active" type="checkbox" name="force" id="force" checked>
            <label class="form-check-label active" for="force">Make item unequippable</label>
        </div>

        <button type="submit" class="btn btn-success shadow-sm">Force Wear Item</button>
    </form>
</div>
@endsection