@extends('layouts.app')

@section('title', 'Forum')

@section('meta')
<meta property="og:title" content="{{ config('app.name') }} Forum">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current(); }}">
<meta property="og:image" content="/images/logos/small.png">
<meta property="og:description" content="This is the {{ config('app.name') }} forum where all of our users can partake in discussion about anything. Logged out users are free to read everything that happens here.">
<meta name="theme-color" content="#0000FF">
@endsection

@section('content')
<div class="container-fluid px-md-5">
    <div class="px-3">
        <div class="row">
            @include('forum.sidebar')
            <div class="col">
                <div class="col-body">
                    <div class="overflow-auto rounded-0 border-right-0 border-left-0 border-top-0 card bg-transparent">
                        <table class="table table-hover mb-0">
                            <thead>
                                <th class="text-muted p-1 border-top-0 border-bottom-0" style="width: 60%">Category</th>
                                <th class="text-muted p-1 text-center border-top-0 border-bottom-0">Threads</th>
                                <th class="text-muted p-1 text-center border-top-0 border-bottom-0">Posts</th>
                                <th class="text-muted p-1 text-center border-top-0 border-bottom-0">Last Post</th>
                            </thead>
                            <tbody>
                                @foreach ($categories as $category)
                                    <tr>
                                        <td>
                                            <a class="text-decoration-none" href="{{ route('forum.category', $category->id) }}">
                                                <div class="text-dark">
                                                    <h4 class="mb-0 font-weight-bold">{{ $category->name }}</h4>
                                                    <p class="mb-0 text-muted">{{ $category->description }}</p>
                                                </div>
                                            </a>
                                        </td>
                                        <td class="align-middle text-muted text-center font-weight-bold">{{ $category->threads()->count() }}</td>
                                        <td class="align-middle text-muted text-center font-weight-bold">{{ $category->threads()->count() + $category->posts()->count() }}</td>
                                        <td class="align-middle text-muted text-center text-muted"><small>{{ $category->updated_at->diffForHumans() }}</small></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
