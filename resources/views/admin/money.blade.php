@inject('user', 'App\Http\Controllers\UsersController')

@extends('layouts.admin')

@section('title', 'Give ' . config('app.currency_name_multiple'))

@section('content')
<div class="container">
    <h1><b>Give {{ config('app.currency_name_multiple') }}</b></h1>
    <p>Give a user {{ config('app.currency_name_multiple') }}. You can use a negative number to take away {{ config('app.currency_name_multiple') }}. Max 10,000 {{ config('app.currency_name_multiple') }} per request.<br>This <i>gives</i> {{ config('app.currency_name_multiple') }} - it doesn't change a users total {{ config('app.currency_name_multiple') }}.</p>
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
    <form method="POST" action="{{ route('admin.changemoney') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" class="form-control" id="username" placeholder="Username">
        </div>
        <div class="form-group">
            <label for="amount">Amount</label>
            <input type="number" onwheel="this.blur()" name="amount" class="form-control" id="amount" placeholder="Amount">
        </div>
        <button type="submit" class="btn btn-success shadow-sm"><i class="fas fa-coins mr-1"></i>Give {{ config('app.currency_name_multiple') }}</button>
    </form>
</div>
@endsection