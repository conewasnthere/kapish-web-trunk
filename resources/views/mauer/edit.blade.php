@extends('layouts.mauer')

@section('title', 'Edit Scribble')

@section('content')
<div class="container">
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header text-white bg-primary">Edit Scribble</div>
        <div class="card-body">
            @if (session()->has('error'))
                <div class="alert alert-danger">
                    {{ session()->get('error') }}
                </div>
            @endif
            <form method="POST" action="{{ route('mauer.edit', $scribble->id) }}" enctype="multipart/form-data">
                @csrf
        
                <div class="form-group">
                    <label for="title">Title (max 100 chars)</label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" id="title" placeholder="Title" value="{{ $scribble->title }}" required>

                    @error('title')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
        
                <div class="form-group">
                    <label for="body">Body (max 2000 chars)</label>
                    <textarea name="body" class="form-control @error('body') is-invalid @enderror" id="body" rows="6" required>{{ $scribble->body }}</textarea>

                    @error('body')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input active" type="checkbox" name="anonymous" id="anonymous" {{ $scribble->anonymous ? 'checked' : '' }}>
                    <label class="form-check-label active" for="announce">Post anonymously</label>
                </div>

                <button type="submit" class="btn btn-success btn-block shadow-sm"><i class="fas fa-save mr-1"></i>Save Edit</button>
            </form>
        </div>
    </div>
</div>
@endsection