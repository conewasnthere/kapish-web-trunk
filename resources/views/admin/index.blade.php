@inject('user', 'App\Http\Controllers\UsersController')

@extends('layouts.admin')

@section('title', 'Admin')

@section('content')
<div class="container admin-panel">
    <div class="">

    </div>
    @if (session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
    @endif
    <h1><b>Admin Panel</b></h1>
    <hr>
    <ul class="list-unstyled px-0">
        <div class="row">
            <div class="col-md my-md-0 my-3 card mx-3">
                <div class="card-body">
                    <h3 class="font-weight-bold mb-0"><i class="fa fa-draw-polygon mr-2"></i>Render Queue</h3>
                    <hr>
                    <div class="row mx-0 py-2">
                        <div class="col d-inline-block text-center">
                            <h5 class="text-primary mb-0 font-weight-bold"><i class="fa fa-tasks mr-2"></i>Queued Jobs</h5>
                            <p class="text-muted py-0 my-0">{{Queue::size()}}</p>
                        </div>
                        <div class="col d-inline-block text-center">
                            <h5 class="text-danger mb-0 font-weight-bold"><i class="fa fa-times mr-2"></i>Failed Jobs</h5>
                            <p class="text-muted py-0 my-0">{{DB::table('failed_jobs')->count()}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		
		<hr />
		
		<div class="row">
			<div class="col-sm-3 my-2">
				<h3 class="text-muted">Surveillance</h3>
				<li><a href="{{ route('admin.log') }}">Admin Log</a></li>
				<li><a href="{{ route('admin.gamejoins') }}">Server Join Log</a></li>
			</div>
			
			<div class="col-sm-3 my-2">
				<h3 class="text-muted">Assets</h3>
				<li><a href="{{ route('admin.assets') }}">Asset Approval</a></li>
				<li><a href="{{ route('admin.xmlitem') }}">New XML Item</a></li>
				<li><a href="{{ route('admin.item') }}">Reward Item</a></li>
				<li><a href="{{ route('admin.wearitem') }}">Force Wear Item</a></li>
				<li><a href="{{ route('admin.renderasset') }}">Re-render Asset</a></li>
			</div>
			
			<div class="col-sm-3 my-2">
				<h3 class="text-muted">Users</h3>
				<li><a href="{{ route('admin.banlist') }}">Ban List</a></li>
				<li><a href="{{ route('admin.ban') }}">Ban User</a></li>
				<li><a href="{{ route('admin.unban') }}">Unban User</a></li>
				<li><a href="{{ route('admin.money') }}">Give {{ config('app.currency_name_multiple') }}</a></li>
				<li><a href="{{ route('admin.booster') }}">Toggle Booster Club</a></li>
				<li><a href="{{ route('admin.donator') }}">Toggle Donator</a></li>
				<li><a href="{{ route('admin.forceunlinkdiscord') }}">Force Unlink Discord</a></li>
				@if (config('app.die_mauer')) <li><a href="{{ route('admin.scribbler') }}">Toggle Scribbler</a></li> @endif
			</div>
			
			<div class="col-sm-3 my-2">
				<h3 class="text-muted">Site</h3>
				<li><a href="{{ route('admin.sitealert') }}">Create Site Alert</a></li>
				<li><a href="{{ route('admin.invitekeys') }}">Manage Existing Invite Keys</a></li>
				<li><a href="{{ route('admin.createinvitekey') }}">Create New Invite Key</a></li>
			</div>
			
			<div class="col-sm-12 my-2">
				<hr />
				
				<h3 class="text-muted">Backend</h3>
				<li><a class="text-danger" href="{{ route('admin.clientsettings') }}">Manage Client FFlags</a></li>
				<li><a class="text-danger" href="{{ route('admin.truncategametokens') }}">Clear All Game Tokens</a></li>
				<li><a class="text-danger" href="{{ route('admin.truncateservers') }}">Clear All Servers</a></li>
			</div>
		</div>
    </ul>
</div>
@endsection
