@extends('layouts.app')

@section('title', $post->title)

@section('meta')
<meta property="og:title" content="{{ $post->title }} - a post by {{ $post->user->username }}">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current(); }}">
<meta property="og:image" content="/images/logos/small.png">
<meta property="og:description" content="{{ $post->body }}">
<meta name="theme-color" content="#0000FF">
@endsection

@section('content')
<div class="container-fluid px-md-5">
    <div class="px-3">
        <div class="row">
            <div class="col-md-2 mb-3 my-md-0">
                <div class="list-group">
                    @if (Auth::check())
                        <div class="border-bottom pb-2 d-flex justify-content-md-start justify-content-center">
                            <img class="position-relative img-fluid rounded-circle headshot-bg" style="max-height: 2.5rem;" data-tadah-thumbnail-type="user-headshot" data-tadah-thumbnail-id="{{ Auth::user()->id }}" src="{{ asset('images/thumbnail/blank.png') }}">
                            <div class="d-inline-block align-middle mx-2">
                                <div class="font-weight-bold mb-0">
                                    <h5 class="font-weight-bold mb-0">{{Auth::user()->username}}</h5>
                                </div>
                                <div class="d-inline-flex">
                                    <span class="text-muted mb-0">
										{{ Auth::user()->getPostsString() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center w-100 my-2">
                            @if (Auth::check())
                                @if (!$category->admin_only)
                                    @if (!$post->locked)
                                    <div class="mb-0 w-100">
                                        <a class="btn btn-success w-100" href="{{ route('forum.createreply', $post->id) }}"><i class="fas fa-plus mr-1" aria-hidden="true"></i>New Reply</a>
                                    </div>
                                    @else
                                        <p class="mb-0 text-muted"><small>This thread has been locked.</small></p>
                                    @endif
                                @else
                                    @if (Auth::user()->isAdmin())
                                        <div class="mb-0 w-100">
                                            <a class="btn w-100 btn-success shadow-sm" href="{{ route('forum.createthread', $category->id) }}"><i class="fas fa-plus mr-1" aria-hidden="true"></i>New Post</a>
                                        </div>
                                    @else
                                        <p class="mb-0 w-100 text-muted"><small>You can't post here.</small></p>
                                    @endif
                                @endif
                            @endif
                        </div>
                    @endif
                    <div class="text-muted">Forum Categories</div>
                    @foreach ($categories as $cate)
                    <a href="{{route('forum.category', $cate->id)}}" class="text-decoration-none {{ Request::segment(2) == $cate->id ? 'font-weight-bold' : 'font-weight-normal'}}">
                        {{$cate->name}}
                    </a>
                    @endforeach
                </div>
            </div>

            <div class="col">
                @if (session()->has('message'))
                    <div class="alert alert-success">
                        {{ session()->get('message') }}
                    </div>
                @endif
                <div class="card shadow-sm">
                    <div class="card-header text-white @if ($post->stickied) bg-success @else bg-primary @endif"><a class="text-white" href="{{ route('forum.index') }}">{{ config('app.name') }} Forum</a> / <a class="text-white" href="{{ route('forum.category', $category->id) }}">{{ $category->name }}</a> / <a class="text-white" href="{{ route('forum.getthread', $post->id) }}">{{ $post->title }}</a></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="d-block d-md-inline-block">
                                    <span class="text-{{(Cache::has('last_online' . $post->user->id) ? 'primary' : 'secondary')}} d-inline-block"><i style="font-size: 50%" class="fas fa-circle align-middle mr-1"></i></span>
                                    <p class="m-0 d-inline-block"><a @if ($post->user->isAdmin()) class="font-weight-bold text-danger" @endif href="{{ route('users.profile', $post->user->id) }}">{{ $post->user->username }}</a></p>
                                </div>
                                <br>
                                <img class="img-fluid" style="max-height: 128px;" data-tadah-thumbnail-id="{{ $post->user->id }}" data-tadah-thumbnail-type="user-thumbnail" src="{{ asset('images/thumbnail/blank.png') }}" alt="{{ $post->user->username }}">
                                <p class="m-0">@if ($post->user->isAdmin()) <p class="text-danger mt-0 mb-0 font-weight-bold"><i class="fas fa-shield mr-1"></i>Administrator</p> @endif Joined: <p class="text-muted d-inline">{{ date('m/d/Y', strtotime($post->user->joined)) }}</p><br>Posts: <p class="text-muted d-inline">{{ $post->user->countPosts() }}</p></p>
                            </div>
                            <div class="col-md-10">
                                <p class="text-muted mb-0"><small>Posted on {{ date('F j, Y, g:i A', strtotime($post->created_at)) }} ({{ $post->created_at->diffForHumans() }})</small></p>
                                <div id="post-{{ $post->id }}-body" class="contain">
                                    @if($post->user->isAdmin() || $post->user->id == 66)
                                        @parsedown($post->body)
                                    @else
                                        {{ $post->body }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if (Auth::check())
                            @if (Auth::user()->id == $post->user->id || Auth::user()->isAdmin())
                                <div class="mt-2 float-right">
                                    <a class="btn btn-primary btn-sm" href="{{ route('forum.editthread', $post->id) }}"><i class="fas fa-edit mr-1" aria-hidden="true"></i>Edit</a>
                                    @if(Auth::user()->isAdmin())
                                    <form style="display: inline;" method="POST" action="{{ route('forum.togglelock', $post->id)  }}">
                                    @csrf

                                        <button class="btn btn-secondary btn-sm shadow-sm" type="submit"><i class="fas fa-lock mr-1"></i>@if ($post->locked) Unlock @else Lock @endif</button>
                                    </form>
                                    <form style="display: inline;" method="POST" action="{{ route('forum.togglesticky', $post->id)  }}">
                                        @csrf

                                        <button class="btn btn-success btn-sm shadow-sm" type="submit"><i class="fas fa-thumbtack mr-1"></i>@if ($post->stickied) Unsticky @else Sticky @endif</button>
                                    </form>
                                    <form style="display: inline;" method="POST" action="{{ route('forum.deletethread', $post->id) }}">
                                        @csrf

                                        <button class="btn btn-danger btn-sm shadow-sm" type="submit"><i class="fas fa-trash mr-1"></i>Delete</button>
                                    </form>
                                    @endif
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
                @if($replies->count() > 0)
                <div class="card shadow-sm my-3">
                    @foreach ($replies as $reply)
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="d-block d-md-inline-block">
                                        <span class="text-{{(Cache::has('last_online' . $reply->user->id) ? 'primary' : 'secondary')}} d-inline-block"><i style="font-size: 50%" class="fas fa-circle align-middle mr-1"></i></span>
                                        <p class="m-0 d-inline-block"><a @if ($reply->user->isAdmin()) class="font-weight-bold text-danger" @endif href="{{ route('users.profile', $reply->user->id) }}">{{ $reply->user->username }}</a></p>
                                    </div>
                                    <br>
                                    <img class="img-fluid" style="max-height: 128px;" data-tadah-thumbnail-id="{{ $reply->user->id }}" data-tadah-thumbnail-type="user-thumbnail" src="{{ asset('images/thumbnail/blank.png') }}" alt="{{ $reply->user->username }}">
                                    <p class="m-0">@if ($reply->user->isAdmin()) <p class="text-danger mt-0 mb-0 font-weight-bold"><i class="fas fa-shield mr-1"></i>Administrator</p> @endif Joined: <p class="text-muted d-inline">{{ date('m/d/Y', strtotime($reply->user->joined)) }}</p><br>Posts: <p class="text-muted d-inline">{{ $reply->user->countPosts() }}</p></p>
                                </div>
                                <div class="col-md-10">
                                    <p class="text-muted mb-0"><small>Posted on {{ date('F j, Y, g:i A', strtotime($reply->created_at)) }} ({{ $reply->created_at->diffForHumans() }})</small></p>
                                    <div id="reply-{{ $reply->id }}-body" class="contain">
                                        @if($reply->user->isAdmin() || $reply->user->id == 66)
                                            @parsedown($reply->body)
                                        @else
                                            {{ $reply->body }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if (Auth::check())
                                @if (Auth::user()->isAdmin())
                                    <div class="mt-2 float-right">
                                        <a class="btn btn-primary btn-sm" href="{{ route('forum.editreply', $reply->id) }}"><i class="fas fa-edit mr-1" aria-hidden="true"></i>Edit</a>
                                        <form style="display: inline;" method="POST" action="{{ route('forum.deletereply', $reply->id) }}">
                                            @csrf

                                            <button class="btn btn-danger btn-sm shadow-sm" type="submit"><i class="fas fa-trash mr-1"></i>Delete</button>
                                        </form>
                                    </div>
                                @endif
                            @endif
                        </div>
                        <hr class="my-0 mx-3">
                    @endforeach
                </div>
                @endif
                <div class="d-flex justify-content-center">
                    {{ $replies->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
