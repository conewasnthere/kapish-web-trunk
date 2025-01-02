@inject('user', 'App\Http\Controllers\UsersController')

@extends('layouts.admin')

@section('title', 'Re-render Asset')

@section('content')
<div class="container">
    <h1><b>Re-render Asset</b></h1>
    <p>Request a Render Job for any asset on {{config('app.name')}}.</p>
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
    <form method="POST" action="{{ route('admin.renderasset') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="itemid">Asset ID</label>
            <input type="text" onwheel="this.blur()" name="assetid" class="form-control" id="assetid" placeholder="Asset ID">
        </div>
        <div class="form-group">
            <label for="type">Type</label>
            <select class="form-control" id="type" name="type" required>
                <option>Item</option>
                <option>Place</option>
                <option>User</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success shadow-sm">Re-render</button>
    </form>
</div>
@endsection