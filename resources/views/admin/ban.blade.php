@inject('user', 'App\Http\Controllers\UsersController')

@extends('layouts.admin')

@section('title', 'Ban')

@section('content')
<div class="container">
    <h1><b>Ban user</b></h1>
    <p class="text-danger">Ban a user from {{ config('app.name') }}, temporarily or forever.</p>
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
    <form method="POST" action="{{ route('admin.banuser') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" class="form-control" value="{{request()->username}}" id="username" placeholder="Username">
        </div>
        <div class="form-group">
            <label for="banreason">Ban reason</label>
            <textarea name="banreason" class="form-control" id="banreason" rows="2"></textarea>
        </div>
        <div class="form-group">
            <label for="unbandate">Banned until</label>
            <div class="input-group">
                <input type="text" name="unbandate" class="form-control" id="unbandate" placeholder="yyyy-mm-dd">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-danger shadow-sm"><i class="fas fa-user-slash mr-1"></i>Ban User</button>
    </form>
</div>
<script>
    $('#unbandate').datepicker({
        format: "yyyy-mm-dd"
    });
</script>
@endsection