@inject('user', 'App\Http\Controllers\UsersController')

@extends('layouts.app')

@section('title', 'Servers')

@section('meta')
<meta property="og:title" content="Servers - {{ config('app.name') }}">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current(); }}">
<meta property="og:image" content="/images/logos/small.png">
<meta property="og:description" content="These are some publicly available servers on {{ config('app.name') }}. They are all hosted by our users.">
<meta name="theme-color" content="#0000FF">
@endsection

@section('content')
<div class="container">
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif

	<div class="d-flex justify-content-between">
		<h1 class="font-weight-bold mb-0">Servers</h1>
		<div class="d-flex align-items-center">
			<a class="btn btn-primary mx-2" href="/download"><i class="fas fa-download mr-1"></i>Download</a>
			<a class="btn btn-success" href="/servers/create"><i class="fas fa-plus mr-1"></i>New Server</a>
		</div>
	</div>

    <hr>

	<div class="alert alert-danger">
		<i class="fas fa-exclamation-triangle"></i> <b>Please note that IP addresses are exposed to server hosts when you connect.</b> Please use a VPN and be safe! This applies vice versa to server hosts.
	</div>

    @if ($servers->count() > 0)
		<div class="row col-xs-12">
			@foreach ($servers as $server)
				<div class="col-lg-3 col-6 mb-3">
					<div class="card card-body d-flex flex-column shadow-sm">
						<a href="{{ route('servers.server', $server->uuid) }}"><img class="card-img-top rounded" style="max-height: 250px;" data-tadah-thumbnail-id="{{ $server->uuid }}" data-tadah-thumbnail-type="place-thumbnail" alt="{{$server->name}} thumbnail" src="{{ asset('images/thumbnail/blank_place.png') }}"></a>
						<hr>
						<div class="d-flex justify-content-between">
							<div class="card-title mb-0 h-auto">
								<h5 class="d-inline-block m-0">
									<a class="text-secondary" href="{{ route('servers.server', $server->uuid) }}">{{ \Illuminate\Support\Str::limit($server->name, 16) }}</a>
								</h5>
								<div class="d-block justify-content-start">
									<span class="badge badge-secondary text-white shadow-sm badge-pill user-select-1">{{ $server->version }}</span>
									@if (Cache::has('server_online' . $server->id))
										<span class="badge badge-pill badge-success text-small"><i style="font-size: 50%" class="fas fa-circle align-middle mr-1"></i><b>Online ({{ sizeof(Cache::get('server_online' . $server->id, 0)) }}/{{ $server->maxplayers }})</b></span>
									@else
										<span class="badge badge-pill badge-secondary text-small"><i style="font-size: 50%" class="fas fa-circle align-middle mr-1"></i>Offline</span>
									@endif
								</div>
							</div>

							<div class="d-flex align-items-center">
								<figure class="position-relative d-inline m-0 figure">
									<a class="text-decoration-none" href="{{ route('users.profile', $server->creator) }}">
										<div class="position-relative text-center h-100">
											<img class="position-relative rounded-circle headshot-bg border mx-1" data-tadah-thumbnail-id="{{ $server->creator }}" data-tadah-thumbnail-type="user-headshot" width="35" src="{{ asset('images/thumbnail/blank.png') }}" width="35">
											@if (\App\Models\User::find($server->creator)->verified_hoster)
												<div data-toggle="tooltip" data-placement="left" title="Verified Hoster" style="right: 0; bottom: 0;" class="position-absolute badge badge-primary text-white shadow-sm badge-pill user-select-none ml-1"><i class="fas fa-badge-check"></i></div>
											@endif
										</div>
										<figcaption class="text-center link-primary">{{ \Illuminate\Support\Str::limit(\App\Models\User::find($server->creator)->username, 8) }}</figcaption>
									</a>
								</figure>
							</div>
						</div>
					</div>
				</div>
			@endforeach
		</div>

        <div class="d-flex justify-content-center">
            {{ $servers->links('pagination::bootstrap-4') }}
        </div>
    @else
        <div class="text-center">
			<img src="/images/blobs/open_mouth.png" class="img-fluid py-2" width=100>
            <h1>No servers</h1>
            <p>Seems like no one's around.</p>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
	$(document).ready(function(){
        $('.dropdown-toggle').dropdown()
    });
</script>
@endsection
