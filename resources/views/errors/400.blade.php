@extends('layouts.error')

@section('title', '400')

@section('content')
<div class="card card-body shadow-sm">
    <div class="text-center px-3 py-3">
        <img class="error-blob-img" src="/images/blobs/sweaty.png">
        <hr>
        <h3>400</h3>
        <span>The server could not understand the request.</span>
        <div class="d-flex justify-content-center mt-3">
            <button class="btn btn-secondary mr-2 shadow-sm" role="button" onclick="window.history.back()">Go back</button>
            <a href="/" class="btn btn-primary" role="button">Home</a>
        </div>
    </div>
</div>
@endsection