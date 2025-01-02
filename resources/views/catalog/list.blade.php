@extends('layouts.app')

@section('title', __('Catalog'))

@section('content')
<div class="container">
    <div class="d-flex justify-content-between">
        @if(request()->get('search'))
            <h3 class="mb-0">
                Results for <span class="text-muted">{{request()->get('search')}}</span>
            </h3>
        @else
            <h1 class="mb-0"><b>{{ __('Catalog') }}</b></h1>
        @endif
        <div class="d-flex align-items-center">
            <a class="btn btn-success" href="{{ route('catalog.upload') }}"><i class="fas fa-plus mr-1"></i>{{ __('New Item') }}</a>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col col-md-3 col-lg-2 mb-3">
            <div class="card card-body p-3 shadow-sm">
                <h4><b>Category</b></h4>
                
                <div class="form-check">
                    <input class="form-check-input" type="radio" id="hats" name="category">
                    <label class="form-check-label" for="hats">{{ __('Hats') }}</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" id="shirts" name="category">
                    <label class="form-check-label" for="shirts">{{ __('Shirts') }}</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" id="pants" name="category">
                    <label class="form-check-label" for="pants">{{ __('Pants') }}</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" id="tshirts" name="category">
                    <label class="form-check-label" for="tshirts">{{ __('T-Shirts') }}</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" id="faces" name="category">
                    <label class="form-check-label" for="faces">{{ __('Faces') }}</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" id="gears" name="category">
                    <label class="form-check-label" for="gears">{{ __('Gears') }}</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" id="heads" name="category">
                    <label class="form-check-label" for="heads">{{ __('Heads') }}</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" id="packages" name="category">
                    <label class="form-check-label" for="packages">{{ __('Packages') }}</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" id="audio" name="category">
                    <label class="form-check-label" for="audio">{{ __('Audio') }}</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" id="images" name="category">
                    <label class="form-check-label" for="images">{{ __('Images') }}</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" id="meshes" name="category">
                    <label class="form-check-label" for="meshes">{{ __('Meshes') }}</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" id="models" name="category">
                    <label class="form-check-label" for="models">{{ __('Models') }}</label>
                </div>
            </div>
        </div>

        <div class="catalog-container col col-md">
            <form method="get" class="px-2 mb-3">
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
                                <a href="{{ route('item.view', $item->id) }}" class="text-decoration-none">
                                    <div class="position-relative text-center my-2">
										@if ($item->type == 'Audio')
										<img src="{{ asset('images/thumbnail/audio.png') }}" alt="{{ $item->name }} {{ __('thumbnail') }}" class="card-img-top p-2" width="128" style="border-radius: .75rem; max-height: 128px; max-width: 128px; object-fit: scale-down;" height="128px">
										@else
										<img src="{{ asset('/images/thumbnail/blank.png') }}" data-tadah-thumbnail-type="item-thumbnail" data-tadah-thumbnail-id="{{ $item->id }}" alt="{{ $item->name }} {{ __('thumbnail') }}" class="card-img-top p-2" width="128" style="border-radius: .75rem; max-height: 128px; max-width: 128px; object-fit: scale-down;" height="128px">
										@endif
										
										<img src="{{ $item->getOverlay() }}" class="position-absolute" style="bottom: 0;left: 0px;">
									</div>
									<div class="mt-1 text-truncate">{{ $item->name }}</div>
                                </a>

                                <hr class="my-2">

                                <div class="text-muted mt-1 mb-1">
									<small>
										@if ($item->original_price > 0)
											<b style="display:block;color: #34c51c;">Was: <img src="{{ asset('/images/currency.png') }}" width="16" height="20"> {{ number_format($item->original_price) }}</b>
											<span style="display:block">Now: <img src="{{ asset('/images/currency.png') }}" width="16" height="20"> {{ number_format($item->price) }}</span>
										@else
											<span style="display:block">Price: <img src="{{ asset('/images/currency.png') }}" width="16" height="20"> {{ number_format($item->price) }}</span>
										@endif
										
										@if ($item->isLimited() || $item->isLimitedUnique())
										<span style="display:block">{{ ($item->private_sellers) ? $item->private_sellers : "No" }} Private Seller{{ ($item->private_sellers == 1) ? "" : "s" }}</a></span>
										@endif
										
										<span style="display:block">Creator: <a href="{{ route('users.profile', $item->user->id) }}" class="text-decoration-none">{{ $item->user->username }}</a></span>
									</small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="col text-center">
                    <br>
                    <img src="{{ asset('/images/blobs/exhausted.png') }}" class="img-fluid">
                    <h2>{{ __('Nothing found') }}</h2>
                    <p>{{ __('Looks like there are no items to display for this query.') }}</p>
                </div>
            @endif

            <div class="d-flex justify-content-center">
                {{ $items->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection
