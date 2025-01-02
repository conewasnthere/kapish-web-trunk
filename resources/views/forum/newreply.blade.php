@extends('layouts.app')

@section('title', 'New Reply')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header text-white bg-primary"><a class="text-white" href="{{ route('forum.index') }}">{{ config('app.name') }} Forum</a> / <a class="text-white" href="{{ route('forum.category', $post->category->id) }}">{{ $post->category->name }}</a> / <a class="text-white" href="{{ route('forum.getthread', $post->id) }}">{{ $post->title }}</a> / New Reply</div>
        <div class="card-body">
            @if (session()->has('error'))
                <div class="alert alert-danger">
                    {{ session()->get('error') }}
                </div>
            @endif
            <form method="POST" action="{{ route('forum.docreatereply', $post->id) }}" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="body">Body <span class="text-muted">(max 2000 chars)</span></label>
                    <textarea placeholder="Please keep the {{config('app.name')}} rules in mind." name="body" class="form-control @error('body') is-invalid @enderror" id="body" rows="6"></textarea>

                    @error('body')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                
                <button type="submit" class="btn btn-success btn-block shadow-sm"><i class="fas fa-plus mr-1"></i>Reply</button>
            </form>
        </div>
    </div>
</div>
@endsection
