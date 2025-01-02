@extends('layouts.error')

@section('title', '500')

@section('content')
<div class="card card-body shadow-sm">
    <div class="text-center px-3 py-3">
        <img class="error-blob-img" src="/images/blobs/scared.png">
        <hr>
        <h3>500</h3>
        <span>An internal server error has occurred.</span>
        <div class="d-flex justify-content-center mt-3">
            <button class="btn btn-secondary mr-2 shadow-sm" role="button" onclick="window.history.back()">Go back</button>
            <a href="/" class="btn btn-primary" role="button">Home</a>
        </div>
    </div>
</div>
@endsection