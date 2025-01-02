@extends('layouts.app')

@section('title', 'New Thread')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header text-white bg-primary"><a class="text-white" href="{{ route('forum.index') }}">{{ config('app.name') }} Forum</a> / <a class="text-white" href="{{ route('forum.category', $category->id) }}">{{ $category->name }}</a> / New Post</div>
        <div class="card-body">
            @if (session()->has('error'))
                <div class="alert alert-danger">
                    {{ session()->get('error') }}
                </div>
            @endif
            <form method="POST" action="{{ route('forum.docreatethread', $category->id) }}" enctype="multipart/form-data">
                @csrf
        
                <div class="form-group">
                    <label for="title">Title <i class="text-muted">(max 100 chars)</i></label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" id="title" placeholder="Title">

                    @error('title')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
        
                <div class="form-group">
                    <label for="body">Body <i class="text-muted">(max 2000 chars)</i></label>
                    <textarea placeholder="Please keep the {{config('app.name')}} rules in mind." name="body" class="form-control @error('body') is-invalid @enderror" id="body" rows="6"></textarea>

                    @error('body')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-success btn-block shadow-sm"><i class="fas fa-plus mr-1"></i>Post</button>
            </form>
        </div>
    </div>
</div>
@endsection
