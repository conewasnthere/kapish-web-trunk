@inject('user', 'App\Http\Controllers\UsersController')

@extends('layouts.admin')

@section('title', 'Create Site Alert')

@section('content')
<div class="container">
    <h1><b>Create Site Alert</b></h1>
    <p>Create an alert that will be shown around the entirety of {{ config('app.name') }}.</p>
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
    <form method="POST" action="{{ route('admin.createsitealert') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="alert">Alert (leave empty to remove alert)</label>
            <input type="text" name="alert" class="form-control" id="alert" placeholder="Kyle Wagness has hacked this website.">
        </div>

        <div class="form-group">
            <label for="type">Color</label>
            <select class="form-control" id="color" name="color" required>
                <option>Red</option>
                <option>Yellow</option>
                <option>Green</option>
            </select>
        </div>
        
        <button type="submit" class="btn btn-success shadow-sm">Alert</button>
    </form>
</div>
@endsection