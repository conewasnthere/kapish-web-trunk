@inject('user', 'App\Http\Controllers\UsersController')

@extends('layouts.admin')

@section('title', 'Reward Item')

@section('content')
<div class="container">
    <h1><b>Reward Item</b></h1>
    <p>Rewards an item to any user on {{ config('app.name') }}. Can be used for contests, giveaways, or if you're an admin and don't want to buy something legitimately.</p>
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
    <form method="POST" action="{{ route('admin.rewarditem') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" class="form-control" id="username" placeholder="Username">
        </div>
        <div class="form-group">
            <label for="itemid">Item ID</label>
            <input type="number" onwheel="this.blur()" name="itemid" class="form-control" id="itemid" placeholder="Item ID">
        </div>
        <button type="submit" class="btn btn-success shadow-sm"><i class="fas fa-gift mr-1"></i>Reward Item</button>
    </form>
</div>
@endsection