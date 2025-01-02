@extends('layouts.error')

@section('title', '503')

@section('content')
<div class="card card-body shadow-sm">
    <div class="text-center px-3 py-3">
        <img class="error-blob-img" src="/images/blobs/inquisitive.png">
        <hr>
        <h3>502</h3>
        <span>The server, while working as a gateway to get a response needed to handle the request, got an invalid response.</span>
        <div class="d-flex justify-content-center mt-3">
            <button class="btn btn-secondary mr-2 shadow-sm" role="button" onclick="window.history.back()">Go back</button>
            <a href="/" class="btn btn-primary" role="button">Home</a>
        </div>
    </div>
</div>
@endsection