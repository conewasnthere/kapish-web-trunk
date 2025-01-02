@extends('layouts.app')

@section('title', 'Friends')

@section('content')
<div class="container">
    <h1 class="font-weight-bold">Friends</h1>
    <hr>

    <div class="card rounded-0 mb-3 shadow-sm">
        <div class="card-body py-0 px-0">
            <ul class="nav nav-pills nav-fill" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link rounded-0 active" id="pills-friends-tab" data-toggle="pill" href="#pills-friends" role="tab" aria-controls="pills-friends" aria-selected="true">Friends</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link rounded-0" id="pills-requests-tab" data-toggle="pill" href="#pills-requests" role="tab" aria-controls="pills-requests" aria-selected="false">Requests</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-friends" role="tabpanel" aria-labelledby="pills-friends-tab">
            <div class="container px-0">
                <h4 class="font-weight-normal">Friends (<span id="friends-count">0</span>)</h4>
                <hr>
                <div id="friends-container" class="row">

                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="pills-requests" role="tabpanel" aria-labelledby="pills-requests-tab">
            <div class="container px-0">
                <h4 class="font-weight-normal">Friend Requests (<span id="requests-count">0</span>)</h4>
                <hr>
                <div id="requests-container" class="row">

                </div>
            </div>
        </div>
    </div>
</div>

<template id="empty">
    <div class="w-100 text-center">
        <img data-toggle="tooltip" data-placement="left" title=":)" src="/images/blobs/egg.png" class="img-fluid py-2" width=100>
        <h2>No one's around.</h2>
        <p class="text-muted">Seems like no one wants to be friends with you. We're sorry.</p>
    </div>
</template>

<template id="friend-template">
    <div class="col-12 col-md-4 mb-3">
        <div class="card card-body">
            <div class="row p-2">
                <div class="d-inline-block position-relative h-100">
                    <a href="/users/${id}/profile">
                        <img style="max-height: 6rem" class="headshot-bg position-relative rounded-circle border mx-1" class="position-relative rounded-circle border mx-1" data-tadah-thumbnail-id="${id}" data-tadah-thumbnail-type="user-headshot">
                        <div class="${online-style} status-border position-absolute shadow-lg" style="right: 0; bottom: 0; height: 25px; width: 25px; border-radius: 50%; display: inline-block"></div>
                    </a>
                </div>
                <div class="d-block px-2">
                    <a href="/users/${id}/profile"><h4 class="font-weight-bold mb-0">${username}</h4></a>
                    <p class="text-muted">${online}</p>
                </div>
            </div>
        </div>
</template>

<template id="request-template">
    <div class="col-12 col-md-4 mb-3">
        <div class="card rounded-0 px-0 py-0">
            <div class="card-body py-0 px-0">
                <div class="py-2">
                    <div class="text-center py-2">
                        <a href="/users/${id}/profile">
                            <img style="max-height: 6rem" class="headshot-bg position-relative rounded-circle border mx-1" class="position-relative rounded-circle border mx-1" data-tadah-thumbnail-id="${id}" data-tadah-thumbnail-type="user-headshot">
                        </a>
                    </div>
                    <div class="text-center px-2 py-1">
                        <h4 class="font-weight-bold">${username}</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col pr-0">
                        <button onclick="tadah.requestFriendship(${id})" type="submit" name="submit" class="w-100 btn btn-primary rounded-0">Accept</button>
                    </div>
                    <div class="col pl-0">
                        <button onclick="tadah.ignoreFriendship(${id})" type="submit" name="submit" class="w-100 btn btn-secondary rounded-0">Ignore</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
@endsection

@section('scripts')
<script>

</script>
@endsection