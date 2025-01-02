@extends('layouts.app')

@section('title', 'Edit Reply')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header text-white bg-primary"><a class="text-white" href="{{ route('forum.index') }}">{{ config('app.name') }} Forum</a> / <a class="text-white" href="{{ route('forum.category', $reply->category->id) }}">{{ $reply->category->name }}</a> / <a class="text-white" href="{{ route('forum.getthread', $reply->thread->id) }}">{{ $reply->thread->title }}</a> / Edit Reply</div>
        <div class="card-body">
            @if (session()->has('error'))
                <div class="alert alert-danger">
                    {{ session()->get('error') }}
                </div>
            @endif
            <form method="POST" action="{{ route('forum.doeditreply', $reply->id) }}" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="body">Body <span class="text-muted">(max 2000 chars)</span></label>
                    <textarea name="body" class="form-control @error('body') is-invalid @enderror" id="body" rows="6">{{ $reply->body }}</textarea>

                    @error('body')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-success btn-block shadow-sm"><i class="fas fa-save mr-1"></i>Save Reply</button>
            </form>
        </div>
    </div>
</div>
@endsection
