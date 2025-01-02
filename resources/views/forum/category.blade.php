@extends('layouts.app')

@section('title', $category->name)

@section('meta')
<meta property="og:title" content="{{ $category->name }} - {{ config('app.name') }} Forum">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current(); }}">
<meta property="og:image" content="/images/logos/small.png">
<meta property="og:description" content="{{ $category->description }}">
<meta name="theme-color" content="#0000FF">
@endsection

@section('content')
<div class="container-fluid px-md-5">
    <div class="px-3">
        <div class="row">
            @include('forum.sidebar')
            <div class="col">
                <div class="w-100 bg-primary p-4 rounded mb-3 align-content-center">
                    <h1 class="font-weight-bold text-light">{{$category->name}}</h1>
                    <p class="text-light m-0">{{$category->description}}</p>
                </div>
                <div class="col-body">
                    <div class="overflow-auto rounded-0 border-right-0 border-left-0 border-top-0 card bg-transparent">
                    <table class="table table-hover mb-0 border-bottom-0">
                        <thead class="border-top-0">
                            <th class="text-muted p-1 pl-2 border-top-0 border-bottom-0" style="width: 70%">Title</th>
                            <th class="text-muted p-1 text-center border-top-0 border-bottom-0">Users</th>
                            <th class="text-muted p-1 text-center border-top-0 border-bottom-0">Replies</th>
                            <th class="text-muted p-1 text-center border-top-0 border-bottom-0">Last Post</th>
                        </thead>
                        <tbody>
                            @foreach ($posts as $post)
                                <tr>
                                    <td class="py-3 px-2 align-middle">
                                        <a class="text-decoration-none" href="{{ route('forum.getthread', $post->id) }}">
                                            <div class="@if ($post->stickied) text-success @else text-dark @endif">
                                                <div class="d-inline-block align-middle w-100 text-truncate">
                                                    <div class="d-inline-block align-middle">
                                                        <img data-tadah-thumbnail-id="{{ $post->user->id }}" data-tadah-thumbnail-type="user-headshot" src="{{ asset('images/thumbnail/blank.png') }}" style="border-width: 3px !important; background-color: #c3c3c3" class="rounded-circle @if ($post->user->isAdmin()) border border-danger @endif mr-1" width="45">
                                                    </div>
                                                    <div class="d-inline-block align-middle w-75">
                                                        <div class="font-weight-bold mb-0">
                                                            {{-- This is really dumb because of icons --}}
                                                            @if ($post->stickied && $post->locked)
                                                                <i class="fas fa-thumbtack mr-1"></i>
                                                                <i class="fas fa-lock mr-1"></i>
                                                                <h5 class="d-inline font-weight-bold mb-0">{{ $post->title }}</h5>
                                                            @elseif ($post->stickied)
                                                                <i class="fas fa-thumbtack mr-1"></i>
                                                                <h5 class="d-inline font-weight-bold mb-0">{{ $post->title }}</h5>
                                                            @elseif ($post->locked)
                                                                <i class="fas fa-lock mr-1"></i>
                                                                <h5 class="d-inline font-weight-bold mb-0">{{ $post->title }}</h5>
                                                            @else
                                                                <h5 class="d-inline font-weight-bold mb-0">{{ $post->title }}</h5>
                                                            @endif
                                                        </div>
                                                        <div class="d-block">
                                                            <span class="text-muted mb-0">
                                                                {{ $post->created_at->isoFormat('LL') }}
                                                            </span>
                                                            <span class="text-muted mb-0 mx-2">
                                                                {{ $post->replies->count() }} replies
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </td>

                                    <td class="pt-3 pb-3 align-middle text-center">
                                        @php
                                            $latestReply = \App\Models\ForumPost::where('thread_id', $post->id)->latest()->first();
                                        @endphp
                                        <div class="position-relative">
                                            <a data-toggle="tooltip" data-placement="left" title="{{$post->user->username}}" href="{{route('users.profile', $post->user->id)}}">
                                                <img data-tadah-thumbnail-id="{{ $post->user->id }}" data-tadah-thumbnail-type="user-headshot" src="{{ asset('images/thumbnail/blank.png') }}" style="border-width: 3px !important; background-color: #c3c3c3" class="rounded-circle @if ($post->user->isAdmin()) border border-danger @endif mr-1" width="45">
                                            </a>
                                            @if($latestReply)
                                                <a data-toggle="tooltip" data-placement="bottom" title="{{$latestReply->user->username}}" href="{{route('users.profile', $latestReply->user->id)}}">
                                                    <img data-tadah-thumbnail-id="{{ $latestReply->user->id }}" data-tadah-thumbnail-type="user-headshot" src="{{ asset('images/thumbnail/blank.png') }}" style="border-width: 3px !important; background-color: #c3c3c3; bottom: 0; right: 0;" class="position-absolute rounded-circle @if ($latestReply->user->isAdmin()) border border-danger @else status-border @endif mr-1" width="30">
                                                </a>
                                            @endif
                                        </div>
                                    </td>

                                    <td class="font-weight-bold pt-3 pb-3 align-middle text-muted text-center">{{ $post->replies()->count() }}</td>
                                    <td class="pt-3 pb-3 align-middle text-muted text-center">
                                        <small>{{ $post->updated_at->diffForHumans() }}</small>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-3">
                    {{ $posts->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
