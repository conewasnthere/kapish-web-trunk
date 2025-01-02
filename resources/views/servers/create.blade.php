@extends('layouts.app')

@section('title', 'Create Server')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">{{ __('Create Server') }}</div>
                <div class="card-body">
                    @if (config('app.server_creation_enabled'))
                        <form method="POST" action="{{ route('servers.create') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Server Name') }}</label>

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
                                    <textarea id="description" type="text" class="form-control @error('description') is-invalid @enderror" name="description" value="{{ old('description') }}"></textarea>

                                    @error('description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="version" class="col-md-4 col-form-label text-md-right">{{ __('Version') }}</label>

                                <div class="col-md-6">
                                    <select class="form-control" id="version" name="version" required>
                                        @foreach (config('app.clients') as $client => $version)
                                            <option>{{ $client }}</option>
                                        @endforeach
                                    </select>
                                    @error('version')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <hr>

                            <div class="form-group row">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="unlisted" id="unlisted">

                                        <label class="form-check-label" for="unlisted">
                                            {{ __('Unlisted') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6 offset-md-4">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="maxplayers" class="col-md-4 col-form-label text-md-right">{{ __('Max Players') }}</label>

                                <div class="col-md-6">
                                    <input id="maxplayers" type="number" onwheel="this.blur()" class="form-control" name="maxplayers" value="" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="chattype" class="col-md-4 col-form-label text-md-right">{{ __('Chat Type') }}</label>

                                <div class="col-md-6">
                                    <select class="form-control @error('chattype') is-invalid @enderror" id="chattype" name="chattype" required>
                                        <option value="0">Classic</option>
                                        <option value="1">Bubble</option>
                                        <option value="2">Classic and Bubble</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="place" class="col-md-4 col-form-label text-md-right @error('place') is-invalid @enderror">{{ __('Place') }}</label>

                                <div class="col-md-6">
                                    <input type="file" class="form-control-file @error('place') is-invalid @enderror" name="place" required>

                                    @error('place')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col">
                                    <button type="submit" class="w-100 btn btn-primary shadow-sm">
                                        <i class="fas fa-plus mr-1"></i>Create
                                    </button>
                                </div>
                            </div>
                        </form>
                    @else
                        <h2 class="text-center">Server creation disabled</h2>
                        <p class="text-center">Sorry, server creation has been disabled. Check back later.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
