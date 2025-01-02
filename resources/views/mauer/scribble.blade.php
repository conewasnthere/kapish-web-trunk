@extends('layouts.mauer')

@section('title', 'Scribble')

@section('content')
<div class="container">
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header text-white bg-primary">New Scribble</div>
        <div class="card-body">
            @if (session()->has('error'))
                <div class="alert alert-danger">
                    {{ session()->get('error') }}
                </div>
            @endif
            <form method="POST" action="{{ route('mauer.scribble') }}" enctype="multipart/form-data">
                @csrf
        
                <div class="form-group">
                    <label for="title">Title (max 100 chars)</label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" id="title" placeholder="Title" required value="{{ old('title') }}">

                    @error('title')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
        
                <div class="form-group">
                    <label for="body">Body (max 2000 chars)</label>
                    <textarea name="body" class="form-control @error('body') is-invalid @enderror" id="body" rows="6" required>{{ old('body') }}</textarea>

                    @error('body')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input active" type="checkbox" name="anonymous" id="anonymous" {{ old('anonymous') ? 'checked' : '' }}>
                    <label class="form-check-label active" for="announce">Post anonymously</label>
                </div>

                <button type="submit" class="btn btn-success btn-block shadow-sm"><i class="fas fa-plus mr-1"></i>Post</button>
            </form>
        </div>
    </div>
</div>
@endsection