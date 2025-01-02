@extends('layouts.app')

@section('title', 'Upload Item')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">{{ __('Upload Item') }}</div>

                <div class="card-body">
                    @if (config('app.item_creation_enabled'))
                        <form method="POST" action="{{ route('catalog.upload') }}" enctype="multipart/form-data">
                            @csrf

                            @if (session()->has('error'))
                                <div class="alert alert-danger">
                                    {{ session()->get('error') }}
                                </div>
                            @endif

                            @if(config('app.asset_upload_cost') != 1)
                          <p class="text-center"><i class="text-primary fas fa-info-circle mr-1"></i>{{ __('Uploading an asset costs') }} <strong>{{ config('app.asset_upload_cost') }} {{ config('app.currency_name_multiple') }}.</strong></p>
                          @else
                            <p class="text-center"><i class="text-primary fas fa-info-circle mr-1"></i>{{ __('Uploading an asset costs') }} <strong>{{ config('app.asset_upload_cost') }} {{ config('app.currency_name') }}.</strong></p>
                          @endif

                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Item Name') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autofocus>

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="description" class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>

                                <div class="col-md-6">
                                    <textarea id="description" type="text" class="form-control @error('description') is-invalid @enderror" name="description" value="{{ old('description') }}" required></textarea>

                                    @error('description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="type" class="col-md-4 col-form-label text-md-right">{{ __('Item Type') }}</label>

                                <div class="col-md-6">
                                    <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                                        <option>{{ __('T-Shirt') }}</option>
                                        <option>{{ __('Shirt') }}</option>
                                        <option>{{ __('Pants') }}</option>
                                        <option>{{ __('Image') }}</option>
                                        <option>{{ __('Mesh') }}</option>
                                        <option>{{ __('Model') }}</option>
                                        <option>{{ __('Audio') }}</option>
                                        @if (Auth::user()->isStaff())
                                            <option>{{ __('Face') }}</option>
                                        @endif
                                    </select>
                                    @error('type')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="price" class="col-md-4 col-form-label text-md-right">{{ __('Price') }}</label>

                                <div class="col-md-6">
                                    <input type="number" onwheel="this.blur()" class="form-control @error('price') is-invalid @enderror" name="price" required>

                                    @error('price')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="asset" class="col-md-4 col-form-label text-md-right @error('asset') is-invalid @enderror">{{ __('Asset') }}</label>

                                <div class="col-md-6">
                                    <input type="file" class="form-control-file @error('asset') is-invalid @enderror" name="asset" required>

                                    @error('asset')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary shadow-sm">
                                        {{ __('Upload') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    @else
                        <h2>{{ __('Item creation disabled') }}</h2>
                        <p>{{ __('Sorry, item creation has been disabled. Check back later.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
