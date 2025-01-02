@extends('layouts.admin')

@section('title', __('Asset Approval'))

@section('content')
<div class="modal fade" id="review-modal" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Review Audio</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				 <audio controls src="" class="w-100">If you're seeing this, your browser doesn't support the audio tag.</audio>
			</div>
			<div class="modal-footer">
				<div class="mx-auto w-75">
					<form method="POST" action="" enctype="multipart/form-data" id="modal-asset-form" class="btn-group w-100" role="group">
						@csrf
						<button type="submit" name="submit" class="btn btn-primary w-50" value="Approve">Accept</button>
						<button type="submit" name="submit" class="btn btn-secondary w-50" value="Deny">Deny</button>
					</form>
					<br />
					<a type="button" class="btn btn-sm btn-danger mt-1 w-100" id="modal-moderate-user">Moderate User</a>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="container">
    <div class="d-flex justify-content-between">
        <h1 class="mb-0">{{ __('Asset Approval') }}</h1>
    </div>
    <hr>
    <div class="row">
        <div class="catalog-container col col-md">
            <form method="get" class="mb-3">
                <div class="input-group shadow-sm">
                    <input class="form-control" type="search" placeholder="{{ __('Search') }}" name="search" aria-label="{{ __('Search') }}" value="">
                    <span class="input-group-append"><button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button></span>
                </div>
            </form>

            @if ($items->count() > 0)
                <div class="row mx-auto">
                    @foreach ($items->all() as $item)
                        <div class="col-xl-2 col-lg-3 col-md-3 col-sm-4 col-6 pb-3 px-2">
                            <div class="card card-body shadow-sm p-2">
                                <div class="position-relative">
                                    <a href="{{ route('item.view', $item->id) }}" class="text-decoration-none">
                                        <div class="text-truncate">
                                            {{ $item->name }}
                                        </div>
										@if ($item->type == 'Audio')
											<img src="{{ asset('images/thumbnail/audio.png') }}" alt="{{ $item->name }} {{ __('thumbnail') }}" class="card-img-top p-2" style="border-radius: .75rem; max-height: 256px;">
										@else
											<img src="{{ asset('images/thumbnail/blank.png') }}" data-tadah-thumbnail-type="item" data-tadah-thumbnail-id="{{ $item->id }}" alt="{{ $item->name }} {{ __('thumbnail') }}" class="card-img-top p-2" style="border-radius: .75rem; max-height: 256px;">
                                        @endif
										
										@if($item->type == 'Shirt' || $item->type == 'Pants')
                                            <div class="position-absolute" style="left: 0; bottom: 0">
                                                <a class="text-decoration-none" target="_blank" href="{{route('item.template', $item->id)}}">View template</a>
                                            </div>
                                        @endif
                                        <div class="position-absolute" style="bottom: 0; right: 0">
                                            <a data-toggle="tooltip" data-placement="left" title="{{$item->user->username}}" href="{{ route('users.profile', $item->user->id) }}" class="text-decoration-none">
                                                <img src="{{ asset('images/thumbnail/blank.png') }}" data-tadah-thumbnail-type="user-headshot" data-tadah-thumbnail-id="{{ $item->user->id }}" style="background-color: rgb(195, 195, 195)" class="rounded-circle border mr-1" width="40">
                                            </a>
                                        </div>
                                    </a>
                                </div>
                                <hr class="my-1">
								@if($item->type == 'Audio')
									<button class="btn-sm btn btn-primary" onclick="reviewAudio({{ $item->id }}, '{{ $item->user->username }}')">Review</button>
								@else
									<form method="POST" action="{{ route('admin.approve', $item->id) }}" enctype="multipart/form-data">
										@csrf
										<div class="btn-group w-100" role="group">                                                                                        
											<button style="width: 50%" type="submit" name="submit" class="btn btn-sm btn-primary" value="Approve">Accept</button>
											<button style="width: 50%" type="submit" name="submit" class="btn btn-sm btn-secondary" value="Deny">Deny</button>
										</div>
									</form>
									<a href="{{route('admin.ban', ['username' => $item->user->username])}}" class="btn-sm btn btn-danger mt-1">Moderate User</a>
								@endif
						   </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="col text-center">
                    <br>
                    <img src="{{ asset('/images/blobs/tired.png') }}" class="img-fluid">
                    <h2>{{ __('Nothing found') }}</h2>
                    <p>{{ __('There are no assets pending approval.') }}</p>
                </div>
            @endif

            <div class="d-flex justify-content-center">
                {{ $items->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function reviewAudio(id, username)
{
	const modal = document.getElementById('review-modal');
	const reviewModal = new bootstrap.Modal(modal, {});
	modal.getElementsByTagName('audio')[0].src = (window.location.origin + `/asset?id=${id}`);
	document.getElementById('modal-moderate-user').href = (window.location.origin + `/admin/ban?username=${username}`);
	document.getElementById('modal-asset-form').action = (window.location.origin + `/admin/${id}/approve`);
	reviewModal.show();
}
</script>
@endsection