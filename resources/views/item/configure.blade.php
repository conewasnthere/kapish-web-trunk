@extends('layouts.app')

@section('title', 'Configure Item')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">{{ __('Configure Item') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('item.configure', $item->id) }}" enctype="multipart/form-data">
                        @csrf

                        @if (session()->has('error'))
                            <div class="alert alert-danger">
                                {{ session()->get('error') }}
                            </div>
                        @endif

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Item Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $item->name }}" required autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="thumbnail" class="col-md-4 col-form-label text-md-right">{{ __('Thumbnail') }}</label>

                            <div class="col-md-6">
                                <img data-tadah-thumbnail-id="{{ $item->id }}" data-tadah-thumbnail-type="item-thumbnail" src="{{ asset('images/thumbnail/blank.png') }}" style="object-fit: contain;" alt="{{ $item->name }}" width="250" height="250">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="description" class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>

                            <div class="col-md-6">
                                <textarea id="description" type="text" class="form-control @error('description') is-invalid @enderror" name="description" required>{{ $item->description }}</textarea>

                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        @if ($item->isXmlAsset() && Auth::user()->isAdmin())
                        <div class="form-group row">
                            <label for="thumbnailurl" class="col-md-4 col-form-label text-md-right">{{ __('Thumbnail URL') }}</label>

                            <div class="col-md-6">
                                <input id="thumbnailurl" type="text" class="form-control @error('thumbnailurl') is-invalid @enderror" name="thumbnailurl" value="{{ $item->thumbnail_url }}">

                                @error('thumbnailurl')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        @endif

                        @if ($item->isXmlAsset() || $item->type == "Lua")
                            @if (Auth::user()->isAdmin())
                            <div class="form-group row">
                                <label for="xml" class="col-md-4 col-form-label text-md-right">{{ __('XML Data') }}</label>

                                <div class="col-md-6">
                                    <textarea id="xml" type="text" class="form-control @error('xml') is-invalid @enderror" name="xml" rows="3" required>{{ $item->getContents() }}</textarea>

                                    @error('xml')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            @endif
                        @endif
						
						@if (Auth::user()->isAdmin())
							
						<div class="form-group row">
							<div class="col-md-6 offset-md-4">
								<div class="form-check">
									<input class="form-check-input " type="radio" name="marketplace_type" id="none" value="none" {{ $item->isResellable() && $item->isBoostersOnly() ?: "checked" }}>
									
									<label class="form-check-label " for="none">
										{{ __('Normal item functionality') }}
									</label>
								</div>
							</div>
						</div>
							
						<div class="form-group row">
							<div class="col-md-6 offset-md-4">
								<div class="form-check">
									<input class="form-check-input " type="radio" name="marketplace_type" id="is_boosters_only" value="boosters" {{ !$item->isBoostersOnly() ?: "checked" }}>
									
									<label class="form-check-label " for="is_boosters_only">
										{{ __('Make this item boosters club only') }}
									</label>
								</div>
							</div>
						</div>
						
						<div class="form-group row">
							<div class="col-md-6 offset-md-4">
								<div class="form-check">
									<input class="form-check-input " type="radio" name="marketplace_type" id="is_limited" value="limited" {{ !$item->isLimited() ?: "checked" }}>
									
									<label class="form-check-label " for="is_limited">
										{{ __('Make this item a limited') }}
									</label>
								</div>
							</div>
						</div>
						
						<div class="form-group row">
							<div class="col-md-6 offset-md-4">
								<div class="form-check">
									<input class="form-check-input " type="radio" name="marketplace_type" id="is_limitedu" value="limitedu" {{ !$item->isLimitedUnique() ?: "checked" }}>
									
									<label class="form-check-label" for="is_limitedu">
										{{ __('Make this item a limited unique') }}
									</label>
								</div>
							</div>
						</div>
						
						<div class="form-group row" id="stock_circulating_div" style="display:{{ $item->is_limitedu ? 'flex' : 'none' }}">
                            <label for="name" class="col-md-4 col-form-label text-md-right">
								{{ __('Amount in circulation') }}
							</label>

                            <div class="col-md-6">
                                <input id="name" type="number" class="form-control @error('stock_circulating') is-invalid @enderror" name="stock_circulating" value="{{ ($item->stock_circulating == 0) ? 5 : $item->stock_circulating }}" required autofocus>

                                @error('stock_circulating')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
						@endif

                        <div class="form-group row">
                            <label for="price" class="col-md-4 col-form-label text-md-right">{{ __('Price') }}</label>

                            <div class="col-md-6">
                                <input type="number" onwheel="this.blur()" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ $item->price }}" required>

                                @error('price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
						
						@if (Auth::user()->isAdmin())
							<div class="form-group row">
                            <label for="price" class="col-md-4 col-form-label text-md-right">
								{{ __('Original Price') }}
								<span class="text-muted" style="display:block">(leave blank to remove, lets users know if discount or price increase)</span>
							</label>

                            <div class="col-md-6">
                                <input type="number" onwheel="this.blur()" class="form-control @error('original_price') is-invalid @enderror" name="original_price" value="{{ $item->original_price }}" required>

                                @error('original_price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
						@endif

                        @if ($item->type == "Lua")
                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input {{ $item->new_signature ? 'active' : '' }}" type="checkbox" name="newsignature" id="newsignature" {{ $item->new_signature ? 'checked' : '' }}>

                                    <label class="form-check-label {{ $item->new_signature ? 'active' : '' }}" for="newsignature">
                                        {{ __('New Signature') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input {{ $item->onsale ? 'active' : '' }}" type="checkbox" name="onsale" id="onsale" {{ $item->onsale ? 'checked' : '' }}>

                                    <label class="form-check-label {{ $item->onsale ? 'active' : '' }}" for="onsale">
                                        {{ __('Put this item on-sale') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group row mb-0 justify-content-center">
                            <div class="col-md-6 text-center">
                                <button type="submit" class="btn btn-primary shadow-sm">
                                    Save Changes
                                </button>
                            </div>
                        </div>
						
						<script>
						$("input[name='marketplace_type']").click(function() {
							$(this).val() == "limitedu" ? $('#stock_circulating_div').show() : $('#stock_circulating_div').hide();
						});
						</script>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
