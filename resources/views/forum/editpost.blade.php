@extends('layouts.app')

@section('title', 'Edit Thread')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header text-white bg-primary"><a class="text-white" href="{{ route('forum.index') }}">{{ config('app.name') }} Forum</a> / <a class="text-white" href="{{ route('forum.category', $post->category->id) }}">{{ $post->category->name }}</a> / <a class="text-white" href="{{ route('forum.getthread', $post->id) }}">{{ $post->title }}</a> / Edit Thread</div>
        <div class="card-body">
            @if (session()->has('error'))
                <div class="alert alert-danger">
                    {{ session()->get('error') }}
                </div>
            @endif
            <form method="POST" action="{{ route('forum.doeditthread', $post->id) }}" enctype="multipart/form-data">
                @csrf
        
                <div class="form-group">
                    <label for="title">Title (max 100 chars)</label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" id="title" placeholder="Title" value="{{ $post->title }}">

                    @error('title')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
        
                <div class="form-group">
                    <label for="body">Body (max 2000 chars)</label>
                    <textarea name="body" class="form-control @error('body') is-invalid @enderror" id="body" rows="6">{{ $post->body }}</textarea>

                    @error('body')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-success btn-block shadow-sm"><i class="fas fa-save mr-1"></i>Save Edit</button>
            </form>
        </div>
    </div>
</div>
@endsection
