@extends('layouts.admin')

@section('title', 'Create Invite Key')

@section('content')
<div class="container">
    <h1><b>Generate Invite Key</b></h1>
    <p>Used for inviting new users to {{ config('app.name') }}. Inviting random or unknown people is not permitted.<br>Minimum uses: 1<br>Maximum uses: 50</p>
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
    <form method="POST" action="{{ route('admin.generateinvitekey') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="uses">Uses</label>
            <input type="number" onwheel="this.blur()" name="uses" class="form-control" id="uses" placeholder="Number of Uses">
        </div>
        <button type="submit" class="btn btn-success shadow-sm"><i class="fas fa-plus mr-1"></i>Create Invite Key</button>
    </form>
</div>
@endsection